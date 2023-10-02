<?php

$rssUrl = $_POST['rss_url'] ?? "https://vnexpress.net/rss/tin-moi-nhat.rss";
$rssData = fetchRss($rssUrl);

// Các hàm xử lý
function fetchRss($rssUrl) {
    $xml = simplexml_load_file($rssUrl);
    if ($xml === false) {
        die('Error fetching RSS feed');
    }
    return $xml;
}

function getContentFromUrl($url) {
    $htmlContent = file_get_contents($url);
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));

    $xpath = new DOMXPath($dom);
    $content = [];
    $titleNodes = $xpath->query('//h1[@class="title-detail"]');
    if ($titleNodes->length > 0) {
        $content['title'] = $titleNodes->item(0)->nodeValue;
    }

    $descriptionNodes = $xpath->query('//p[@class="description"]');
    if ($descriptionNodes->length > 0) {
        $content['description'] = $descriptionNodes->item(0)->nodeValue;
    }

    $content['normal'] = [];
    $normalNodes = $xpath->query('//p[@class="Normal"]');
    for ($i = 0; $i < 3 && $i < $normalNodes->length; $i++) {
        $content['normal'][] = $normalNodes->item($i)->nodeValue;
    }

    return $content;
}


function generateArticle($content, $language) {

    $flattenedContent = [];

    if (isset($content['title'])) {
        $flattenedContent[] = $content['title'];
    }
    
    if (isset($content['description'])) {
        $flattenedContent[] = $content['description'];
    }
    
    if (isset($content['normal']) && is_array($content['normal'])) {
        $flattenedContent = array_merge($flattenedContent, $content['normal']);
    }

    $api_key = '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';
    $endpoint = 'https://api.openai.com/v1/chat/completions';
    $output = '';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ];

    $data = [
        'model' => 'gpt-3.5-turbo-16k',
        'messages' => [
            [
                'role' => 'system',
                'content' => "You are a tool that assists in crafting articles based on the provided content. 
                The generated articles need to maintain the accuracy of the information while being engaging 
                for readers without being overly repetitive in terms of wording and sentence structure."
            ],
            [
                'role' => 'user',
                'content' => "Write me an article in " . $language . " based on my content. Pay attention to 
                dividing the article into paragraphs corresponding to each idea in the content. 
                Content:" . implode("\n", $flattenedContent)
            ]
        ],
        'temperature' => 1,
        'max_tokens' => 5000,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $output = 'Error:' . curl_error($ch);
    } else {
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['choices'][0]['message']['content'])) {
            $output = $decodedResponse['choices'][0]['message']['content'];
        } else {
            $output = "Error with API response: " . json_encode($decodedResponse);
        }
    }

    curl_close($ch);

    return $output;
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['url'])) {
    $content = getContentFromUrl($_POST['url']);
    $generatedArticle = generateArticle($content, $_POST['language']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS with News Generator</title>
</head>
<body>

<?php if (isset($generatedArticle)): ?>
    <h3>Generated Article:</h3>
    <p><?= nl2br($generatedArticle) ?></p>
<?php endif; ?>

<?php foreach ($rssData->channel->item as $item): ?>
    <h2><?= $item->title ?></h2>
    <small>Đăng lúc: <?= $item->pubDate ?></small><br><br>
    <?php
        $description = $item->description;
        $doc = new DOMDocument();
        $descriptionContent = mb_convert_encoding($description, 'HTML-ENTITIES', "UTF-8");
        @$doc->loadHTML($descriptionContent);

        foreach ($doc->getElementsByTagName('a') as $link) {
            $link->parentNode->removeChild($link);
        }

        foreach ($doc->getElementsByTagName('img') as $img) {
            $img->parentNode->removeChild($img);
        }

        $cleanDescription = $doc->textContent;
    ?>

    <p><?= trim($cleanDescription) ?></p>
    <a href="<?= $item->link ?>" target="_blank">Đọc bài viết gốc</a><br>
    <form action="" method="post">
        <input type="hidden" name="url" value="<?= (string)$item->link ?>">
        <label for="language">Language:</label>
        <select name="language" id="language">
            <option value="English">English</option>
            <option selected="selected" value="Vietnamese">Tiếng Việt</option>
        </select>
        <br>
        <input type="submit" value="Viết bài">
    </form>
    <hr>
<?php endforeach; ?>

</body>
</html>
