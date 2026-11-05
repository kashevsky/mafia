<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MafiaImageService
{
    private string $nodePath;
    private string $scriptPath;

    public function __construct()
    {
        // Твой Node из nvm
        $this->nodePath = '/Users/dima/.nvm/versions/node/v16.18.1/bin/node';

        // Правильный путь к скрипту через base_path()
        $this->scriptPath = base_path('scripts/screenshot.js');
    }

    public function generateResultsImage(array $data): string
    {

        $tempDir = storage_path('temp-screenshots');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $htmlPath = tempnam($tempDir, 'mafia_html_') . '.html';
        $pngPath  = tempnam($tempDir, 'mafia_img_') . '.png';

        file_put_contents($htmlPath, $data['html'] ?? '');

        $command = [
            $this->nodePath,
            $this->scriptPath,
            $htmlPath,
            $pngPath,
        ];

        Log::debug('Running Puppeteer command', [
            'command' => implode(' ', $command),
            'htmlPath' => $htmlPath,
            'pngPath'  => $pngPath,
        ]);

        $process = new Process($command, null, [
            'PUPPETEER_BROWSER_PATH' => null,
            'CHROME_PATH'           => null,
        ]);

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                $output      = $process->getOutput();

                Log::error('Puppeteer failed with return code', [
                    'returnCode' => $process->getExitCode(),
                    'output'     => $output ?: '(empty)',
                    'errorOutput'=> $errorOutput ?: '(empty)',
                ]);

                throw new \RuntimeException('Failed to generate screenshot with Puppeteer');
            }

            if (!file_exists($pngPath) || filesize($pngPath) === 0) {
                throw new \RuntimeException('Screenshot file was not created or is empty');
            }

            Log::info('Screenshot generated successfully', ['path' => $pngPath]);
            return $pngPath;

        } catch (ProcessFailedException $e) {
            Log::error('ProcessFailedException caught', ['message' => $e->getMessage()]);
            throw $e;
        } finally {
            // @unlink($htmlPath);
            // @unlink($pngPath); // оставь для отладки, пока не заработает
        }
    }
}
