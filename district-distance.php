<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Pallatrax</title>
  </head>
  <body>

    <form action="pallatrax.php" method="get">
          <label>District A: </label>
          <input type="number" name="dist_1"/><br>
          <label>District B: </label>
          <input type="number" name="dist_2"/><br>
          <input type="submit" value="Submit"/>
        </form>

    <?php
    $districtAdjacency = array(
      1 => array(2, 3, 5, 6, 7, 11, 12),
      2 => array(1, 3, 12),
      3 => array(1, 2, 4, 13),
      4 => array(3, 13, 14, 15),
      5 => array(1, 6, 7, 8, 9, 13),
      6 => array(1, 5, 7, 8, 9, 13, 14),
      7 => array(1, 5, 6, 8, 9, 13, 14),
      8 => array(1, 5, 6, 7, 9, 10, 15),
      9 => array(5, 6, 7, 8, 10, 11, 19, 20, 21),
      10 => array(8, 9, 14, 16, 17, 18, 19),
      11 => array(1, 9, 12, 22),
      12 => array(1, 2, 11),
      13 => array(3, 4, 5, 6, 7, 14),
      14 => array(4, 6, 7, 8, 10, 13, 15, 16),
      15 => array(4, 14, 16),
      16 => array(10, 14, 15, 17),
      17 => array(10, 16, 18),
      18 => array(10, 17, 19, 20, 23),
      19 => array(9, 10, 18, 20),
      20 => array(9, 18, 19, 21, 23),
      21 => array(9, 20),
      22 => array(11),
      23 => array(20, 18),
    );
    ?>

    <?php
    class DistrictDistance
    {
      protected $graph;
      protected $visited = array();

      public function __construct($graph) {
        $this->graph = $graph;
      }

      public function distanceSearch($dist_A, $dist_B) {

        foreach ($this->graph as $vertex => $adj) {
          $this->visited[$vertex] = false;
        }

        $q = new SplQueue();

        $q->enqueue($dist_A);
        $this->visited[$dist_A] = true;

        $path = array();
        $path[$dist_A] = new SplDoublyLinkedList();
        $path[$dist_A]->setIteratorMode(
          SplDoublyLinkedList::IT_MODE_FIFO|SplDoublyLinkedList::IT_MODE_KEEP
        );

        $path[$dist_A]->push($dist_A);

        $found = false;
        while (!$q->isEmpty() && $q->bottom() != $dist_B) {
          $t = $q->dequeue();

          if (!empty($this->graph[$t])) {

            foreach ($this->graph[$t] as $vertex) {
              if (!$this->visited[$vertex]) {
                $q->enqueue($vertex);
                $this->visited[$vertex] = true;
                $path[$vertex] = clone $path[$t];
                $path[$vertex]->push($vertex);
              }
            }
          }
        }

        if (isset($path[$dist_B])) {
          echo "From district $dist_A to district $dist_B in ",
            count($path[$dist_B]) - 1,
            " distance.<br>";
            $sep = '';
              foreach ($path[$dist_B] as $vertex) {
            echo $sep, $vertex;
            $sep = '->';
          }
        }
      }
    }
 ?>

<?php
   $g = new DistrictDistance($districtAdjacency);
    if (isset($_GET)) {
       $dist_1 = $_GET['dist_1'];
       $dist_2 = $_GET['dist_2'];

     if(($dist_1 > 0) && ($dist_1 < 24) && ($dist_2 > 0) && ($dist_2 < 24)){
       $g->distanceSearch($dist_1, $dist_2);
     }else {
         echo "District ".$dist_1." or ".$dist_2." is not a valid district!";
      }
    }
  ?>

 </body>
</html>

<!-- used source: https://www.sitepoint.com/data-structures-4/ -> breadth-first search algorithm -->
