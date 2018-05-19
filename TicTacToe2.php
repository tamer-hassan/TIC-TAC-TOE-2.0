<?php

require_once('lib/ConsoleTable.php');

class TicTacToe2
{
  private $gridSize = 10;
  private $player1 = 'X';
  private $player2 = 'O';
  private $computer = 'C';
  private $emptyChar = '-';

  private $grid = array();
  private $movesCount = 0;

  public function init() {
    $players = array($this->player1, $this->player2, $this->computer);
    shuffle($players);
    $playersIterator = new InfiniteIterator(new ArrayIterator($players));


    for ($i=0; $i < $this->gridSize; $i++) {
      $this->grid[$i] = array_fill(0, $this->gridSize, $this->emptyChar);
    }


    foreach ($playersIterator as $nextPlayer) {

      $table = new TicTacToe2\ConsoleTable();

      for ($i=0; $i < $this->gridSize; $i++) {
        $table->addRow($this->grid[$i]);
      }

      $table->showAllBorders()->display();

      if ($this->movesCount >= $this->gridSize &&
        $this->isGameOver($this->grid, $this->gridSize, $this->movesCount, $this->emptyChar) === true) {
        break;
      }

      if ($nextPlayer == $this->computer) {
          $aiMove = $this->makeRandomMove();
          echo 'Player ' . $this->computer . ' played in ' . $aiMove . "\n\n";
      } else {
        do {
          echo 'Enter x,y coordinates for Player ' . $nextPlayer . ': ';
          $playerInput = rtrim(fgets(STDIN));
          sscanf($playerInput, "%d,%d", $xpos, $ypos);

          if ($xpos > $this->gridSize || $xpos < 1 || $ypos > $this->gridSize || $ypos < 1) {
            echo 'Sorry, out of bounds! Try between 1,1 and '.$this->gridSize.','.$this->gridSize."\n\n";
          }
          else if ($this->grid[$xpos - 1][$ypos - 1] != $this->emptyChar) {
            echo 'Sorry, Player ' . $this->grid[$xpos - 1][$ypos - 1]. " already played there. Try again!\n\n";
          }
        } while ($xpos > $this->gridSize || $xpos < 1 || $ypos > $this->gridSize || $ypos < 1 ||
                $this->grid[$xpos - 1][$ypos - 1] != $this->emptyChar);

        $this->grid[$xpos - 1][$ypos - 1] = $nextPlayer;
      }

      $this->movesCount++;

    }
  }

  /**
	 * Beginner AI
	 *
	 * Makes random move
	 */
	protected function makeRandomMove() {
		$empty_fields = $this->getEmptyFields();
		$move = $empty_fields[rand(0, count($empty_fields) - 1)];
		$this->grid[$move[0]][$move[1]] = $this->computer;
		return ($move[0]+1) . ',' . ($move[1]+1);
	}


  /**
	 * Returns an array with indexes of the empty fields
	 * array[] = array[i][j]
	 */
	protected function getEmptyFields(){
		$fields = array();

		for ($i = 0; $i < $this->gridSize; $i++) {
			for ($j = 0; $j < $this->gridSize; $j++) {
				if ($this->grid[$i][$j] === $this->emptyChar){
					$fields[]= array($i, $j);
				}
			}
		}
		return $fields;
	}

  /**
   * Returns true if a player won or game is a draw,
   * otherwise returns false
   */
  protected function isGameOver() {
    // Check if there's a completed row
    foreach($this->grid as $gridrow) {
      if (end($gridrow) != $this->emptyChar && count(array_unique($gridrow)) === 1) {
        echo 'Player ' . end($gridrow) . " Wins!\n\n";
        return true;
      }
    }

    // Check if there's a completed column
    for ($i=0; $i < $this->gridSize; $i++) {
      if ($this->grid[0][$i] != $this->emptyChar) {
        $colWin = true;
        $player = $this->grid[0][$i];

        for($j=0; $j < $this->gridSize; $j++) {
    			if($this->grid[$j][$i] !== $player) $colWin = false;
    		}

        if ($colWin) {
          echo 'Player ' . $player . " Wins!\n\n";
          return true;
        }
      }

    }

    // Check if first diagonal is completed
    if ($this->grid[0][0] != $this->emptyChar) {
      $diagWin = true;
      $player = $this->grid[0][0];

      for ($i=0; $i < $this->gridSize; $i++) {
        if($this->grid[$i][$i] !== $player) $diagWin = false;
      }

      if ($diagWin) {
        echo 'Player ' . $player . " Wins!\n\n";
        return true;
      }
    }


    // Check if second diagonal is completed
    if ($this->grid[0][$this->gridSize - 1] != $this->emptyChar) {
      $diagWin = true;
      $player = $this->grid[0][$this->gridSize - 1];
      $i = 0;
    	$j = $this->gridSize - 1;

    	while($i < $this->gridSize && $j >= 0) {
    		if($this->grid[$i][$j] !== $player) $diagWin = false;
    		$i++;
    		$j--;
    	}

    	if ($diagWin) {
    		echo 'Player ' . $player . " Wins!\n\n";
    		return true;
    	}
    }


    // Check if it's a tie
    if ($this->movesCount >= pow($this->gridSize, 2)) {
      echo "Draw!\n\n";
      return true;
    }

    return false;
  }
}
