<?php
if ($controller->isInGame()) {
?>
    <form name="input" action="index.php" method="post">
        Enter coordinates (row, col), e.g. A5 <input type="input" size="5" name="coord" autocomplete="off" autofocus="">
        <input type="submit">
    </form>
<?php
} else {
    session_destroy();
?>
    <a href="index.php">Play again</a>
<?php
}