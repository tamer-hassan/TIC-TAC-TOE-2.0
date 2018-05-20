<?php

require_once("TicTacToe2.php");

$config = include('config.php');

$game = new TicTacToe2(
  $config['gridSize'],
  $config['player1'],
  $config['player2'],
  $config['computer'],
  $config['emptyChar']
);

$game->init();
