<?php

class Pawn extends Figure
{
    private const FIRST_WHITE_PAWN_ROW = 2;
    private const FIRST_BLACK_PAWN_ROW = 7;

    public function __toString()
    {
        return $this->isBlack ? '♟' : '♙';
    }

    /**
     * @param array $moveData
     * @return bool
     */
    public function checkMove(array $moveData): bool
    {
        $checkResult = false;
        [$xFrom, $yFrom, $xTo, $yTo, $state] = $moveData;
        $yDiff = $yTo - $yFrom;

        //if pawn moves on vertical
        if ($xFrom === $xTo) {
            if ($this->isBlack) {
                //if pawn moves on one cell
                if ($yDiff === -1) {
                    $checkResult = !isset($state[$xTo][$yTo]);
                }
                //if pawn moves on two cells
                elseif ($yDiff === -2 && $yFrom === self::FIRST_BLACK_PAWN_ROW) {
                    $checkResult = !(isset($state[$xFrom][$yFrom - 1]) || isset($state[$xTo][$yTo]));
                }
            }
            //the same but for another color
            else {
                if ($yDiff === 1) {
                    $checkResult = !isset($state[$xTo][$yTo]);
                } elseif ($yDiff === 2 && $yFrom === self::FIRST_WHITE_PAWN_ROW) {
                    $checkResult = !(isset($state[$xFrom][$yFrom + 1]) || isset($state[$xTo][$yTo]));
                }
            }
        }
        //if pawn takes another figure
        else {
            $rowDiff = abs(Constants::LETTERS_NUMBERS[$xTo] - Constants::LETTERS_NUMBERS[$xFrom]);

            /** @var Figure $figure */
            $figure = $state[$xTo][$yTo] ?? null;

            if ($rowDiff === 1) {
                if (($this->isBlack && $yDiff === -1) || (!$this->isBlack && $yDiff === 1)) {
                    $checkResult = $figure && $this->isBlack !== $figure->isBlack();
                }
            }
        }

        return $checkResult;
    }
}
