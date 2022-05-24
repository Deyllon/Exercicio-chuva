<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country, that is also a player.
 */
class BaseCountry implements CountryInterface {

  /**
   * The name of the country.
   *
   * @var string
   */
  protected $name;
  protected $neighbors;
  protected  $numberOfTrops = 3;

  /**
   * Builder.
   *
   * @param string $name
   *   The name of the country.
   */
  public function __construct(string $name) {
    $this->name = $name;
  }
  /**
   * we return the name of the country
   */
  public function getName(): string
  {
    return $this -> name;
  }

  /**
   * we save the initial neighbors
   */
  public function setNeighbors(array $neighbors): void
  {
    $this -> neighbors = $neighbors; 
  }
  /**
   * we return the neighbors
   */
  public function getNeighbors(): array
  {
    
    return $this -> neighbors;
  }
  /**
   * we return the number of troops
   */
  public function getNumberOfTroops(): int
  {
    return $this -> numberOfTrops;
  }

  /**
   * we check if the number of troops is 0, if it is, it returns true
   */
  public function isConquered(): bool
  {
    if ($this -> numberOfTrops === 0){
      return true;
    }
    return false;
  }

  /**
   * if the country has attached another country, 
   * we take the list of neighbors of the attached country and the country it has attached, 
   * we treat it not to return the country that attached it, 
   * and we take this treated list and save it as the new neighbors of that country. 
   * After that we take each neighbor of the attached country and iterate over the neighbors list of those countries
   * to change the attached country element to the conqueror country, so the other countries will 
   * recognize that they have a new neighbor.
   * And we added one more troop for the conquering country
   */
  public function conquer(CountryInterface $conqueredCountry): void
  {

    $newNeighbors = $conqueredCountry ->  getNeighbors();
    
    $untreatedArray  = array_merge($this ->getNeighbors(), $newNeighbors);
    $neighborsUntreated = array_values(array_unique($untreatedArray, SORT_REGULAR));
    $neighbors = [];
    $nameCountry =  $this -> getName();
    foreach($neighborsUntreated as $index ){
 
      if ($index ->getName() != $nameCountry){
        $neighbors[] = $index;
      }
    }
  
    $this -> neighbors =  $neighbors;
    $this -> numberOfTrops +=1;

    foreach($newNeighbors as $index){
      if ($index != $this){
        foreach($index -> getNeighbors() as $country => $name){
          if($name == $conqueredCountry){
            $index -> neighbors[$country] = $this;
            $index -> neighbors = array_values(array_unique($index -> neighbors, SORT_REGULAR));
          }
        }
      }
    }
  }

  /**
   * we take the total number of troops and subtract it from the number of troops killed
   */
  public function killTroops(int $killedTroops): void
  {
    $numberTrops = $this -> getNumberOfTroops();
    $numberTrops -= $killedTroops;

    $this -> numberOfTrops = $numberTrops;
  }

  /**
   * at the end of each round we add 3 more troops for each country alive
   */
  public function AddTroops(): void
  {
    $this -> numberOfTrops +=3;
  }

}
