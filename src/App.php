<?php
/**
 * This file is part of the TripleI.taxi
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace TripleI\taxi;

class App
{
    private static $meter_limit = 200;

    private $enrai = [['A', 'B', 'C'], [995, 400]];

    private $tansu = [['D', 'E', 'F', 'G'], [845, 350]];
    
    private $price_of_fares = [
        [['A', 'C'], 180, 60],
        [['B', 'C'], 960, 60],
        [['A', 'B'], 1090, 60],
        [['A', 'D'], 540, 50],
        [['C', 'D'], 400, 50],
        [['D', 'F'], 510, 50],
        [['C', 'F'], 200, 60],
        [['D', 'E'], 720, 50],
        [['G', 'E'], 1050, 50],
        [['F', 'G'], 230, 50],
        [['B', 'G'], 1270, 60]
    ];


    private $route_stack = [];

    private $total_fee = 0;



    public function run($data)
    {
        $route_str = $data[0];

        $count_route = strlen($route_str);

        for($i = 0; $count_route - 1 > $i; $i++) {
            $tmp = substr($route_str, $i, 2);
            $this->route_stack[] = [$tmp[0], $tmp[1]];
        }

        $route = $this->nextPoint();

        $this->total_fee = 0;

        // 初乗り運賃算出
        $minimum_fare = $this->getMinimumFare($route);
        $remaining_meter = $minimum_fare[0];
        $total_fee = $minimum_fare[1];

        while($route !== null) {
            $point = $this->getPointOfData($route);
            $remaining_meter = $remaining_meter - $point['distance'];

            while ($remaining_meter < 0) {
                $remaining_meter = $remaining_meter + self::$meter_limit;
                $point = $this->getPointOfData($route);
                $total_fee = $total_fee + $point['charge'];
            }
            $route = $this->nextPoint();
        }

        $this->total_fee = $total_fee;
    }

    /**
     * 次のポイントを配列で返す
     * @return array
     */
    public function nextPoint()
    {
        $route = array_shift($this->route_stack);

        return $route;
    }

    /**
     * FROM TOの値から距離と単価を返す
     * @param array $route FROM, TO
     * @return array
     */
    public function getPointOfData(array $route)
    {
        foreach($this->price_of_fares as $fare) {
            if (count(array_diff($fare[0], $route)) === 0) {
                return ['distance' => $fare[1], 'charge' => $fare[2]];
            }
        }
    }

    /**
     * 初乗り運賃取得
     * @param array $start_point
     * @return
     */
    public function getMinimumFare(array $start_point)
    {
        $origin = $start_point[0];
        $data_collection = [$this->enrai, $this->tansu];
        foreach ($data_collection as $data) {
            if (in_array($origin, $data[0])) {
                return $data[1];
            }
        }
    }

    public function getTotalFee()
    {
        return $this->total_fee;
    }
}
