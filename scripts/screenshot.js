const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

(async () => {
  const htmlPath = process.argv[2];
  const outputPath = process.argv[3];

  if (!htmlPath || !outputPath) {
    console.error('Usage: node screenshot.js <html_path> <output_path>');
    process.exit(1);
  }

  // ВАЖНО: укажи здесь путь к твоему Chrome for Testing (или оставь null, если Puppeteer сам найдёт)
  // Если у тебя уже работает — можно закомментировать эту строку и убрать executablePath из launch()
  const executablePath = '/Users/dima/.cache/puppeteer/chrome/mac_arm-120.0.6099.109/chrome-mac-arm64/Google Chrome for Testing.app/Contents/MacOS/Google Chrome for Testing';

  let browser;
  try {
    browser = await puppeteer.launch({
      // Если хочешь, чтобы Puppeteer сам нашёл браузер, удали executablePath
      executablePath,
      args: [
        '--disable-dev-shm-usage',
        '--disable-gpu',
        // Фиксируем размер окна — это помогает Puppeteer стабильно рендерить fullPage
        '--window-size=1920,1080',
      ],
      headless: 'new',
    });

    const page = await browser.newPage();

    // Самое важное для чёткости на MacBook: deviceScaleFactor: 2
    await page.setViewport({
      width: 1920,
      height: 1080,
      deviceScaleFactor: 2,
    });

    await page.goto(`file://${path.resolve(htmlPath)}`, {
      waitUntil: 'networkidle0', // ждём, пока не закончатся сетевые запросы
    });

    // Ждём появления ключевого элемента (чтобы шрифты точно прогрузились)
    // Если в твоей раскладке есть уникальный класс, лучше использовать его вместо 'h1'
    await page.waitForSelector('h1', { timeout: 5000 });

    // Небольшая пауза, чтобы шрифты окончательно отрисовались (шрифты могут «прыгать» после networkidle)
    await page.waitForTimeout(3000);

    await page.screenshot({
      path: outputPath,
      type: 'png',
      fullPage: true, // скриншот всей страницы (даже если она длиннее экрана)
    });

    if (fs.existsSync(outputPath) && fs.statSync(outputPath).size > 0) {
      console.log(`OK: screenshot saved to ${outputPath}`);
    } else {
      throw new Error('Screenshot file was not created or is empty');
    }
  } catch (err) {
    console.error('Error during screenshot:', err.message);
    process.exit(1);
  } finally {
    if (browser) {
      await browser.close();
    }
  }
})();
