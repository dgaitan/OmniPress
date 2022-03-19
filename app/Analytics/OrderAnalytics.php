<?php

namespace App\Analytics;

use App\Models\WooCommerce\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderAnalytics {

    /**
     * Comparisson Ranges
     */
    const CURRENT_MONTH_RANGE = 'current_month';
    const LAST_MONTH_RAGE = 'last_month';
    const CURRENT_YEAR_RANGE = 'current_year';
    
    /**
     * Comparisson Periods
     */
    const PREVIOUS_PERIOD = 'previous_period';
    const PREVIOUS_YEAR = 'previous_year';

    /**
     * Comparisson group
     *
     * @var string
     */
    protected string $rangeGroup = 'month';

    /**
     * Comparisson group
     *
     * @var string
     */
    protected string $periodGroup = 'month';

    /**
     * Comparisson Range Value
     *
     * @var string
     */
    protected string $comparissonRange = '';

    /**
     * Comparisson Period Value
     *
     * @var string
     */
    protected string $comparissonPeriod = '';

    /**
     * Model to analyze
     *
     * @var string
     */
    protected string $model = Order::class;

    /**
     * Range Queryset
     *
     * @var Builder|null
     */
    protected $rangeQueryset = null;

    /**
     * Period Queryset
     *
     * @var Builder|null
     */
    protected $periodQueryset = null;

    /**
     * Undocumented function
     */
    public function __construct(string $range, string $period) {
        $this->setComparisonRange($range);
        $this->comparissonPeriod = $period;
    }

    /**
     * Get The total Orders
     *
     * @return integer
     */
    public function getTotalOrders(): int {
        return $this->getRangeQueryset()->count();
    }

    /**
     * Get total sold
     *
     * @return integer
     */
    public function getTotalSold(): int {
        return $this->getRangeQueryset()->sum('total');
    }

    /**
     * Get Total Fees
     *
     * @return integer
     */
    public function getTotalFees(): int {
        return $this->getRangeQueryset()->sum(
            DB::raw('total_tax + shipping_tax + shipping_total')
        );
    }

    /**
     * Get Net Sales Amount of current period
     *
     * @return integer
     */
    public function getNetSales(): int {
        return $this->getTotalSold() - $this->getTotalFees();
    }

    /**
     * Get The total Orders
     *
     * @return integer
     */
    public function getPeriodTotalOrders(): int {
        return $this->getPeriodQueryset()->count();
    }

    /**
     * Get total sold
     *
     * @return integer
     */
    public function getPeriodTotalSold(): int {
        return $this->getPeriodQueryset()->sum('total');
    }

    /**
     * Get Total Fees
     *
     * @return integer
     */
    public function getPeriodTotalFees(): int {
        return $this->getPeriodQueryset()->sum(
            DB::raw('total_tax + shipping_tax + shipping_total')
        );
    }

    /**
     * Get Net Sales Amount of current period
     *
     * @return integer
     */
    public function getPeriodNetSales(): int {
        return $this->getPeriodTotalSold() - $this->getPeriodTotalFees();
    }

    public function getSalesPercentageDifference() {
        return $this->calculatePercentageComparisson(
            $this->getNetSales(), $this->getPeriodNetSales()
        );
    }

    public function getTotalOrdersPercentageDifference() {
        return $this->calculatePercentageComparisson(
            $this->getTotalOrders(), $this->getPeriodTotalOrders()
        );
    }

    /**
     * Range Queryset
     *
     * @return Builder
     */
    protected function getRangeQueryset(): Builder {
        if (is_null($this->rangeQueryset)) {
            $this->rangeQueryset = $this->model::whereBetween(
                'date_created',
                $this->getRangeDates()
            )->whereIn('status', ['processing', 'completed']);
        }

        return $this->rangeQueryset;
    }
    
    /**
     * Get Period Queryset
     *
     * @return Builder
     */
    protected function getPeriodQueryset(): Builder {
        return $this->model::whereBetween(
            'date_created',
            $this->getPeriodDates()
        )->whereIn('status', ['processing', 'completed']);
    }

    /**
     * Get Date Ranges
     *
     * @return array
     */
    protected function getRangeDates(): array {
        $ranges = [
            self::CURRENT_MONTH_RANGE => [
                (new Carbon)->startOfMonth(), Carbon::now()
            ],
            self::LAST_MONTH_RAGE => [
                (new Carbon)->subMonth(1)->startOfMonth(),
                (new Carbon)->subMonth(1)->endOfMonth() 
            ],
            self::CURRENT_YEAR_RANGE => [
                (new Carbon)->startOfYear(),
                (new Carbon)->endOfYear()
            ]
        ];

        return $ranges[$this->comparissonRange];
    }

    /**
     * Get Period Dates
     *
     * @return array
     */
    protected function getPeriodDates(): array {
        $dateRanges = $this->getRangeDates();
        
        $periods = [
            self::PREVIOUS_PERIOD => [
                Carbon::create($dateRanges[0])
                    ->{$this->getPeriodSubstractionMethod('sub')}(1)
                    ->{$this->getPeriodSubstractionMethod('startOf')}(),
                Carbon::create($dateRanges[0])
                    ->{$this->getPeriodSubstractionMethod('sub')}(1)
                    ->{$this->getPeriodSubstractionMethod('endOf')}(),
            ],
            self::PREVIOUS_YEAR => [
                Carbon::create($dateRanges[0])->subYear()->startOfYear(),
                Carbon::create($dateRanges[0])->subYear()->endOfYear()
            ]
        ];

        return $periods[$this->comparissonPeriod];
    }

    /**
     * Get the substraction method name to define or load
     * the previous perdio
     *
     * @return string
     */
    protected function getPeriodSubstractionMethod(string $prefix): string {
        return sprintf(
            '%s%s',
            $prefix,
            ucfirst($this->rangeGroup)
        );
    }

    /**
     * Set Comparisson Range
     *
     * @param string $range
     * @return void
     */
    protected function setComparisonRange(string $range): void {
        $readRange = explode('_', $range);
        
        $this->rangeGroup = end($readRange);
        $this->comparissonRange = $range;
    }

    /**
     * Te formula to calculate percentage of comparission
     * between to values is the following one:
     *
     * @param integer $baseValue
     * @param integer $actualValue
     * @return mixed
     */
    protected function calculatePercentageComparisson(int $actualValue, int $baseValue): mixed {
        if ($baseValue === 0 && $actualValue === 0) {
            return 0;
        }

        return (($actualValue - $baseValue) / $baseValue) * 100;
    }
}