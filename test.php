<?php

$url = "https://vnexpress.net/hoang-thai-tu-nhat-ban-tham-viet-nam-hom-nay-4655177.html";

// Lấy toàn bộ nội dung HTML từ URL
$htmlContent = file_get_contents($url);

$dom = new DOMDocument();

// Tải nội dung HTML vào DOMDocument
@$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));

$xpath = new DOMXPath($dom);

// Lấy nội dung của thẻ <h1> có class là "title-detail"
$titleNodes = $xpath->query('//h1[@class="title-detail"]');
if ($titleNodes->length > 0) {
    echo "<h1>" . $titleNodes->item(0)->nodeValue . "</h1><br>";
}

// Lấy nội dung của thẻ <p> có class là "description"
$descriptionNodes = $xpath->query('//p[@class="description"]');
if ($descriptionNodes->length > 0) {
    echo $descriptionNodes->item(0)->nodeValue . "<br>";
}

// Lấy nội dung của 3 thẻ <p> đầu tiên có class là "Normal"
$normalNodes = $xpath->query('//p[@class="Normal"]');
for ($i = 0; $i < 3 && $i < $normalNodes->length; $i++) {
    echo $normalNodes->item($i)->nodeValue . "<br>";
}

?>
