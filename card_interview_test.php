<?php
//create a deck
function createDeck() {
    $suits = [
        'C' => 0, 
        'D' => 1, 
        'H' => 2, 
        'S' => 3
    ];
    $numbers = range(1, 13);
    $deck = [];
    foreach ($suits as $suitKey => $suit) {
        foreach ($numbers as $rank) {
            $card = $suitKey . '-' . $rank;
            $deck[] = $card;
        }
    }
    return $deck;
}

//calculate each person will get how many cards averagely
function averageCardsPerPerson($deck, $numberOfPerson)
{
    $totalCardsPerPerson = count($deck) / $numberOfPerson;
    if($numberOfPerson > count($deck)){
        return ceil($totalCardsPerPerson);
    } else {
        return floor($totalCardsPerPerson);
    }
}

//shuffle and distribute the cards
function distributeCard($deck, $cardPerPerson, $numberOfPerson)
{
    shuffle($deck);
    $deck = changeNumbersToPlayingCards($deck);
    $personArray = range(1, $numberOfPerson);
    // var_dump($personArray);
    $cardCount = 0;
    for ($iteration = 1; $iteration <= $cardPerPerson; $iteration++)
    {
        foreach($personArray as $index){
            $result[$index][] = $deck[$cardCount];
            $cardCount++;
        }
    }
    return beautifyDisplay($result);
}

function changeNumbersToPlayingCards($deck)
{
    foreach($deck as $card)
    {
        $part = explode('-', $card);
        if(!empty($part[1]))
        {
            $playingCards = changeCardOriginalAlphabet((int)$part[1]);
            $playingCardDeck[] = $part[0] . '-' . $playingCards;
        } else {
            trigger_error("Irregular occurred", E_USER_ERROR);
        }
    }
    return $playingCardDeck;
}

//change the cards numbers except number 2-9
function changeCardOriginalAlphabet(int $number)
{
    switch($number){
        case 1:
            $playingCardNumber = 'A';
            break;
        case 10:
            $playingCardNumber = 'X';
            break;
        case 11:
            $playingCardNumber = 'J';
            break;
        case 12:
            $playingCardNumber = 'Q';
            break;
        case 13:
            $playingCardNumber = 'K';
            break;
        default:
            $playingCardNumber = $number;
    }
    return (string)$playingCardNumber;
}

//rearranging the cards to each person
function beautifyDisplay(array $result)
{
    $string = '';
    foreach ($result as $person => $cards) {
        $string .= "Person $person's cards: " . implode(', ', $cards) . "<br>";
    }
    return $string;
}


$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the input data from the form
    $numberOfPerson = (int)$_POST["person"];
    if (!is_numeric($numberOfPerson) || $numberOfPerson < 0 || empty($numberOfPerson)) {
        $error = 'Input value does not exist or value is invalid';
    } else {
        $deck = createDeck();
        $cardPerPerson = averageCardsPerPerson($deck, $numberOfPerson);
        $cardsDistributed = distributeCard($deck, $cardPerPerson, $numberOfPerson);
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card</title>
</head>
<body>
    <form action="index.php" method="post">
        <label for="person">Enter Number of person:</label>
        <input type="text" id="person" name="person" required>
        <input type="submit" value="Submit">
    </form>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif (isset($deck)): ?>
        <!-- Display success message or any other content here -->
        <p style="color: green;">Deck generated successfully! <br><br> <?php echo $cardsDistributed; ?></p>
    <?php endif; ?>
</body>
</html>
