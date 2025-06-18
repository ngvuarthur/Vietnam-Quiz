<?php

session_start();

$questions = [
    1 => [
        'question' => 'Where is Vietnam located?',
        'answers' => ['Europe', 'Asia', 'Africa', 'South America'],
        'correct' => 2
    ],

    2 => [
        'question' => 'What is the capital of Vietnam?',
        'answers' => ['Hồ Chí Minh City', 'Huế', 'Hà Nội', 'Đà Nẵng'],
        'correct' => 3
    ],

    3 => [
        'question' => 'What is the most popular last name in Vietnam? (40% of Vietnamese people has this last name)',
        'answers' => ['Nguyễn', 'Trần', 'Lê', 'Phạm'],
        'correct' => 1
    ],

    4 => [
        'question' => 'Which is a famous Vietnamese noodle soup?',
        'answers' => ['Ramen', 'Laksa', 'Chicken Noodle Soup', 'Phở'],
        'correct' => 4
    ],

    5 => [
        'question' => 'What is Vietnam famous for in Phong Nha?',
        'answers' => ['The world&apos;s largest coffee plantation', 'The world&apos;s longest river', 'The world&apos;s largest cave', 'The world&apos;s tallest mountain'],
        'correct' => 3
    ]
];

//check if the user has started the quiz
if (isset($_POST['start'])) {
    $_SESSION['current_question'] = 1;
    $_SESSION['score'] = 0;
    $_SESSION['feedback'] = [];
}

//check if an answer has been submitted
if (isset($_POST['answer'])) {
    $current = $_SESSION['current_question'];
    $user_answer = intval($_POST['answer']);

    //subtract 1 from the correct answer index to match the 0-based user answer
    if ($user_answer === $questions[$current]['correct'] - 1) {
        $_SESSION['score']++;
        $_SESSION['feedback'][] = "Question $current: Correct!";
    } else {
        $correct_answer = $questions[$current]['answers'][$questions[$current]['correct'] - 1];
        $_SESSION['feedback'][] = "Question $current: Incorrect. The correct answer is $correct_answer.";
    }

    $_SESSION['current_question']++;
}
//get the current question number, or 0 if the quiz hasn't started
$current = isset($_SESSION['current_question']) ? $_SESSION['current_question'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="./styles.css">
</head>

<body>

<header>
    <h1>How much do you know about Vietnam?</h1>
    <?php if ($current === 0): ?>
        <form method="POST">
            <div class="start">
                <button id="start" type="submit" name="start">Start Quiz</button>
            </div>
        </form>
    <?php endif; ?> <!-- Properly closing the if statement -->
</header>

<main>
    <?php if ($current > 0 && $current <= count($questions)): ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="question-container">
                <h1>Question <?php echo $current; ?></h1>
                <p><?php echo $questions[$current]['question']; ?></p>
                <?php foreach ($questions[$current]['answers'] as $index => $answer): ?>
                    <input type="radio" name="answer" value="<?php echo $index; ?>" onchange="submitForm()" required>
                    <?php echo $answer; ?><br>
                <?php endforeach; ?>
                <div class="submit-container">
                <button type="submit">Submit</button>
                </div>
            </div>
            
        </form>
    
        <?php elseif ($current > count($questions)): ?>
            <div class="feedbacks">
            <h2>Quiz Completed!</h2>
            <p>Your score: <?php echo $_SESSION['score']; ?> out of <?php echo count($questions); ?></p>
            <h3>Feedback:</h3>
            <ul>
                <?php foreach ($_SESSION['feedback'] as $feedback): ?>
                    <li><?php echo $feedback; ?></li>
                <?php endforeach; ?>
            </ul>
            <form method="POST">
                <button type="submit" name="start">Restart Quiz</button>
            </form>
            </div>
        <?php endif; ?>
    
</main>

</body>
</html>