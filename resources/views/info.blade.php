<form method="POST" action="/info-send" enctype="multipart/form-data">
    @csrf
    <input type="submit" value="Очистить лог">
</form>
<a href="rating-log.txt" download="rating-log.txt">Скачать лог</a>
