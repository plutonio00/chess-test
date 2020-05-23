<?php

class Pawn extends Figure
{
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
        return $this->isBlack() ?
            $this->checkBlackPawnMove($moveData) : $this->checkWhitePawnMove($moveData);
    }

    private function checkBlackPawnMove(array $moveData): bool
    {
        $checkResult = false;
        list($xFrom, $yFrom, $xTo, $yTo, $state) = $moveData;

        if ($xFrom === $xTo) {
            $yDiff = $yTo - $yFrom;

            if ($yDiff === -1) {
                $checkResult = !isset($state[$xTo][$yTo]);
            } elseif ($yDiff === -2) {
                $checkResult = !(isset($state[$xFrom][$yFrom - 1]) || isset($state[$xTo][$yTo]));
            }
        } elseif (abs(Constants::LETTERS_NUMBERS[$xTo] - Constants::LETTERS_NUMBERS[$xFrom]) === 1) {
            $checkResult = isset($state[$xTo][$yTo]);
        }

        return $checkResult;
    }

    private function checkWhitePawnMove(array $moveData): bool
    {
        $checkResult = false;
        list($xFrom, $yFrom, $xTo, $yTo, $state) = $moveData;

        if ($xFrom === $xTo) {
            $yDiff = $yTo - $yFrom;

            if ($yDiff === 1) {
                $checkResult = !isset($state[$xTo][$yTo]);
            } elseif ($yDiff === 2 && $yFrom === 2) {
                $checkResult = !(isset($state[$xFrom][$yFrom + 1]) || isset($state[$xTo][$yTo]));
            }
        }
        elseif (abs(Constants::LETTERS_NUMBERS[$xTo] - Constants::LETTERS_NUMBERS[$xFrom]) === 1) {
            $checkResult = isset($state[$xTo][$yTo]);
        }

        return $checkResult;
    }
}
