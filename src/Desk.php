<?php

class Desk
{
    private $figures = [];
    private $isWhitesMove;

    public function __construct()
    {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['b'][1] = new Knight(false);
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['d'][1] = new Queen(false);
        $this->figures['e'][1] = new King(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['h'][1] = new Rook(false);

        $this->figures['a'][2] = new Pawn(false);
        $this->figures['b'][2] = new Pawn(false);
        $this->figures['c'][2] = new Pawn(false);
        $this->figures['d'][2] = new Pawn(false);
        $this->figures['e'][2] = new Pawn(false);
        $this->figures['f'][2] = new Pawn(false);
        $this->figures['g'][2] = new Pawn(false);
        $this->figures['h'][2] = new Pawn(false);

        $this->figures['a'][7] = new Pawn(true);
        $this->figures['b'][7] = new Pawn(true);
        $this->figures['c'][7] = new Pawn(true);
        $this->figures['d'][7] = new Pawn(true);
        $this->figures['e'][7] = new Pawn(true);
        $this->figures['f'][7] = new Pawn(true);
        $this->figures['g'][7] = new Pawn(true);
        $this->figures['h'][7] = new Pawn(true);

        $this->figures['a'][8] = new Rook(true);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['d'][8] = new Queen(true);
        $this->figures['e'][8] = new King(true);
        $this->figures['f'][8] = new Bishop(true);
        $this->figures['g'][8] = new Knight(true);
        $this->figures['h'][8] = new Rook(true);
    }

    public function move($move)
    {
        if (!preg_match('/^([a-h])([1-8])-([a-h])([1-8])$/', $move, $match)) {
            throw new MoveException(
                sprintf('Incorrect move %s: there are no such coordinates on the board', $move)
            );
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo = $match[3];
        $yTo = $match[4];

        if (!isset($this->figures[$xFrom][$yFrom])) {
            throw new MoveException(sprintf('Incorrect move %s: cell %s%s is empty', $move, $xFrom, $yFrom));
        }
        elseif ($xFrom === $xTo && $yFrom === $yTo) {
            throw new MoveException(sprintf('Incorrect move %s: the figure remains in place', $move));
        }
        else {
            $figure = $this->figures[$xFrom][$yFrom];
        }

        if (empty($this->isWhitesMove)) {
            $this->isWhitesMove = true;
        } else {
            $this->isWhitesMove = !$this->isWhitesMove;

            if (!$this->checkMovePriority($figure)) {
                throw new MoveException(
                    sprintf('Incorrect move %s: now %s\'s move', $move, $this->isWhitesMove ? 'White' : 'Black')
                );
            }
        }

        if ($figure instanceof Pawn && !$figure->checkMove([$xFrom, (int)$yFrom, $xTo, (int)$yTo, $this->figures])) {
            throw new MoveException('Incorrect move: the pawn doesn’t go that way');
        }

        $this->figures[$xTo][$yTo] = $this->figures[$xFrom][$yFrom];
        unset($this->figures[$xFrom][$yFrom]);
    }

    public function dump()
    {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }

    protected function checkMovePriority(Figure $figure): bool
    {
        return ($figure->isBlack() && !$this->isWhitesMove) || (!$figure->isBlack() && $this->isWhitesMove);
    }
}
