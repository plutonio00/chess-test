<?php

class Pawn extends Figure
{
    const FIRST_WHITE_PAWN_ROW = 2;
    const FIRST_BLACK_PAWN_ROW = 7;

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
        list($xFrom, $yFrom, $xTo, $yTo, $state) = $moveData;
        $yDiff = $yTo - $yFrom;

        if ($xFrom === $xTo) {
            if ($this->isBlack) {
                if ($yDiff === -1) {
                    $checkResult = !isset($state[$xTo][$yTo]);
                } elseif ($yDiff === -2 && $yFrom === self::FIRST_BLACK_PAWN_ROW) {
                    $checkResult = !(isset($state[$xFrom][$yFrom - 1]) || isset($state[$xTo][$yTo]));
                }
            } else {
                if ($yDiff === 1) {
                    $checkResult = !isset($state[$xTo][$yTo]);
                } elseif ($yDiff === 2 && $yFrom === self::FIRST_WHITE_PAWN_ROW) {
                    $checkResult = !(isset($state[$xFrom][$yFrom + 1]) || isset($state[$xTo][$yTo]));
                }
            }
        } else {
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
