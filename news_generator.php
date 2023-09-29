<?php

$api_key = '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';
$endpoint = 'https://api.openai.com/v1/chat/completions';
$output = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $original_content = $_POST['content'];
    $language = $_POST['language'];

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
                'content' => "Write me an article in " .$language. " based on my content. Pay attention to 
                dividing the article into paragraphs corresponding to each idea in the content. 
                Content:" . $original_content
            ]
        ],
        'temperature' => 1,
        'max_tokens' => 10000,
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Content Generator</title>
</head>

<body>

    <form action="news_generator.php" method="post">
        <label for="content">Original Content:</label><br>
        <textarea name="content" id="content" rows="10" cols="50"></textarea><br><br>
        <label for="language">Language:</label>
        <select name="language" id="language">
            <option value="English">English</option>
            <option selected="selected" value="Vietnamese">Tiếng Việt</option>
        </select>
        <br><br>
        <input type="submit" value="Generate">
    </form>

    <h3>Generated Content:</h3>
    <p><?php echo $output; ?></p>

</body>

</html>