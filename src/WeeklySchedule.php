<?php

namespace Puntodev\Bookables;

use Carbon\Carbon;
use DateTime;
use Exception;

class WeeklySchedule
{
    private static array $dowMap = [
        'Sun',
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat'
    ];

    private static array $dowOrder = [
        'Sun' => 0,
        'Mon' => 1,
        'Tue' => 2,
        'Wed' => 3,
        'Thu' => 4,
        'Fri' => 5,
        'Sat' => 6,
    ];

    private array $daily;

    private int $hoursInAdvance;

    private bool $disableAll;

    /**
     * WeeklySchedule constructor.
     *
     * @param array $daily
     * @param int $hoursInAdvance
     * @param bool $disableAll
     */
    private function __construct(
        array $daily = [
            'Sun' => [],
            'Mon' => [],
            'Tue' => [],
            'Wed' => [],
            'Thu' => [],
            'Fri' => [],
            'Sat' => []
        ],
        int $hoursInAdvance = 24,
        bool $disableAll = false
    )
    {
        $this->daily = $daily;
        $this->hoursInAdvance = $hoursInAdvance;
        $this->disableAll = $disableAll;
    }

    /**
     * @param array $array
     *
     * @throws Exception
     */
    private static function validate(array $array): void
    {
        if (!array_key_exists('hours_in_advance', $array)) {
            throw new Exception("Missing hours in advance in schedule: ");
        }
        $hoursInAdvance = $array['hours_in_advance'];
        if (!is_int($hoursInAdvance)) {
            throw new Exception("Invalid hours in advance in schedule: " . $hoursInAdvance);
        }

        if (!array_key_exists('daily', $array)) {
            throw new Exception("Missing daily hours in schedule: ");
        }
        $daily = $array['daily'];
        if (!is_array($daily)) {
            throw new Exception("Invalid daily hours in schedule: " . $daily);
        }

        foreach ($daily as $k => $v) {
            if (!in_array($k, self::$dowMap)) {
                throw new Exception("Invalid key in json representation of schedule: " . $k);
            }
            if (!is_array($v)) {
                throw new Exception("Invalid value in json representation of schedule: key: " . $k . ", value: " . $v);
            }

            foreach ($v as $item) {
                if (!array_key_exists('start', $item)) {
                    throw new Exception("Invalid value in json representation of schedule. Element doesn't have start time: key: " . $k);
                }
                if (!array_key_exists('end', $item)) {
                    throw new Exception("Invalid value in json representation of schedule. Element doesn't have end time: key: " . $k);
                }
                try {
                    $start = new DateTime($item['start']);
                } catch (Exception $e) {
                    throw new Exception("Invalid time in json representation of schedule. Start is not a valid time: key: " . $k . ", start value: " . $item['start']);
                }
                try {
                    $end = new DateTime($item['end']);
                } catch (Exception $e) {
                    throw new Exception("Invalid time in json representation of schedule. End is not a valid time: key: " . $k . ", end value: " . $item['end']);
                }
                if ($start > $end) {
                    throw new Exception("Invalid time range in json representation of schedule. Start time must be before end time: key: " . $k . ", start: " . $item['start'] . ', end: ' . $item['end']);
                }
            }
        }
    }

    public function forDate(Carbon $date): array
    {
        if ($this->disableAll) {
            return [];
        }

        // TODO acá hay un bug, ya que podemos estar eligiendo mal el día si difieren la zona horaria del staff y del customer
        // No es grave porque acá solo lo usamos en una zona horaria, y si hubiera más de una no habrían grandes diferencias
        return $this->forDay(self::$dowMap[$date->dayOfWeek]);
    }

    public function forDay(string $day): array
    {
        if ($this->disableAll) {
            return [];
        }

        return $this->daily[$day];
    }

    public function daily(): array
    {
        return $this->daily;
    }

    public function hoursInAdvance(): int
    {
        return $this->hoursInAdvance;
    }

    public function disableAll(): bool
    {
        return $this->disableAll;
    }

    /**
     * @param string $json
     *
     * @return WeeklySchedule
     * @throws Exception if Json doesn't represent a valid schedule
     */
    public static function fromJson(string $json): WeeklySchedule
    {
        $json_decoded = json_decode($json, true);
        self::validate($json_decoded);

        return self::fromArray($json_decoded);
    }

    /**
     * @param array $array
     *
     * @return WeeklySchedule
     * @throws Exception if Json doesn't represent a valid schedule
     */
    public static function fromArray(array $array): WeeklySchedule
    {
        self::validate($array);

        uksort($array['daily'], fn ($a, $b) => self::$dowOrder[$a] - self::$dowOrder[$b]);

        return new self($array['daily'], $array['hours_in_advance'], $array['disable_all'] ?? false);
    }

    public static function defaultWorkingHours(): array
    {
        return [
            'disable_all' => false,
            'hours_in_advance' => 24,
            'daily' => [
                'Sun' => [
                ],
                'Mon' => [
                    [
                        'start' => '08:00',
                        'end' => '12:00'
                    ],
                    [
                        'start' => '14:00',
                        'end' => '18:00'
                    ]
                ],
                'Tue' => [
                    [
                        'start' => '08:00',
                        'end' => '12:00'
                    ],
                    [
                        'start' => '14:00',
                        'end' => '18:00'
                    ]
                ],
                'Wed' => [
                    [
                        'start' => '08:00',
                        'end' => '12:00'
                    ],
                    [
                        'start' => '14:00',
                        'end' => '18:00'
                    ]
                ],
                'Thu' => [
                    [
                        'start' => '08:00',
                        'end' => '12:00'
                    ],
                    [
                        'start' => '14:00',
                        'end' => '18:00'
                    ]
                ],
                'Fri' => [
                    [
                        'start' => '08:00',
                        'end' => '12:00'
                    ],
                    [
                        'start' => '14:00',
                        'end' => '18:00'
                    ]
                ],
                'Sat' => [
                    [
                        'start' => '10:00',
                        'end' => '12:00'
                    ]
                ]
            ]
        ];
    }
}
