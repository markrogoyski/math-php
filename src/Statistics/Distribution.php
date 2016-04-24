<?php
namespace Math\Statistics;

class Distribution {

  /**
   * Frequency distribution
   * A table that displays the frequency of various outcomes in a sample.
   * Each entry in the table contains the frequency or count of the occurrences of values within a particular group or interval.
   * The table summarizes the distribution of values in the sample.
   * https://en.wikipedia.org/wiki/Frequency_distribution
   *
   * The values of the input array will be the keys of the result array.
   * The count of the values will be the value of the result array for that key.
   *
   * @param array $values Ex: ( A, A, A, B, B, C )
   * @return array frequency distribution Ex: ( A => 3, B => 2, C => 1 )
   */
  public static function frequency( array $values ): array {
    $frequencies = array();
    foreach ( $values as $value ) {
      if ( !isset($frequencies[$value]) ) {
        $frequencies[$value] = 1;
      } else {
        $frequencies[$value]++;
      }
    }
    return $frequencies;
  }
}