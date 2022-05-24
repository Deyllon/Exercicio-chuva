<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay;

use Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface;

/**
 * A manager that will roll the dice and compute the winners of a battle.
 */
class Battlefield implements BattlefieldInterface {


    /** 
     * here we get the number of troops, 
     * after that we draw a number from 1-6 until reaching the number of troops 
     * after that we save and return these numbers in an array
    */
    public function rollDice(CountryInterface $country, bool $isAtacking): array
    {
       $numberTroups =  $country -> getNumberOfTroops();
       $results = [];
       if ($isAtacking == TRUE){
            $numberTroups -= 1;
            for ($i = 1; $i <= $numberTroups; $i++){
                $diceResult = rand(1,6);
                $results[] = $diceResult;
            }
            return $results;
       }
       for ($i = 1; $i <= $numberTroups; $i++){
            $diceResult = rand(1,6);
            $results[] = $diceResult;
       }
       return $results;
    }

    /**
     * Here the array size is stored in 2 variables  and we check which list is bigger and 
     * cuts the array so that both have the same number of elements, 
     * after that we sort the array and check which element is bigger and save the result
     */
    public function computeBattle(CountryInterface $attackingCountry, array $attackingDice, CountryInterface $defendingCountry, array $defendingDice): void
    {
        $attackingListSize = count($attackingDice);
        $defendingListSize = count($defendingDice);
        rsort($defendingDice);
        rsort($attackingDice);
        $defendingKiledTrops = 0;
        $attackingKiledTrops = 0;
        if ($attackingListSize >= $defendingListSize){
            $defendingListSize = count(array_slice($defendingDice,0, $defendingListSize));
            for ($i = 0; $i < $defendingListSize; $i++){
                $valueAttackDice = $attackingDice[$i];
                $valueDefendingDice = $defendingDice[$i];
                if ($valueAttackDice > $valueDefendingDice){
                    $defendingKiledTrops += 1;
                }
                else{
                    $attackingKiledTrops += 1;
                }
            }
            $attackingCountry ->  killTroops($attackingKiledTrops);
            $defendingCountry ->  killTroops($defendingKiledTrops);
        }
        else{
            $attackingListSize = count( array_slice($attackingDice,0, $attackingListSize));
            for ($i = 0; $i <$attackingListSize; $i++){
                $valueAttackDice = $attackingDice[$i];
                $valueDefendingDice = $defendingDice[$i];
                if ($valueAttackDice > $valueDefendingDice){
                    $defendingKiledTrops += 1;
                }
                else{
                    $attackingKiledTrops += 1;
                }
            }
            $attackingCountry ->  killTroops($attackingKiledTrops);
            $defendingCountry ->  killTroops($defendingKiledTrops);
        }
    }

}
