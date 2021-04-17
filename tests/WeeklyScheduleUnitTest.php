<?php

namespace Tests;

use Carbon\Carbon;
use Exception;
use PHPUnit\Framework\TestCase;
use Puntodev\Bookables\WeeklySchedule;

class WeeklyScheduleUnitTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidJsonFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing hours in advance in schedule:');
        WeeklySchedule::fromJson("{}");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidHoursInAdvanceFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid hours in advance in schedule: xx');
        WeeklySchedule::fromJson('{"daily": {"Sun":"invalid"}, "hours_in_advance": "xx"}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testMissingDailyFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing daily hours in schedule:');
        WeeklySchedule::fromJson('{"hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidDailyFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid daily hours in schedule: 23');
        WeeklySchedule::fromJson('{"daily": "23", "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testAllValidKeysWork()
    {
        $valid_keys = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($valid_keys as $key) {
            WeeklySchedule::fromJson($this->buildJsonWithDailyKey($key, 24));
        }
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidDailyKeysFail()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid key in json representation of schedule: Any');
        WeeklySchedule::fromJson($this->buildJsonWithDailyKey('Any', 24));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidValueFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid value in json representation of schedule: key: Sun, value: invalid');
        WeeklySchedule::fromJson('{"daily": {"Sun":"invalid"}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidEntryMissingStartFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid value in json representation of schedule. Element doesn\'t have start time: key: Sun');
        WeeklySchedule::fromJson('{"daily": {"Sun":[{"begin": "13:00", "end": "14:00"}]}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidEntryMissingEndFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid value in json representation of schedule. Element doesn\'t have end time: key: Sun');
        WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "13:00", "finish": "14:00"}]}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidEntryInvalidStartFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid time in json representation of schedule. Start is not a valid time: key: Sun, start value: 25:00');
        WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "25:00", "end": "14:00"}]}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidEntryInvalidEndFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid time in json representation of schedule. End is not a valid time: key: Sun, end value: 25:00');
        WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "25:00"}]}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInvalidEntryStartAfterEndFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid time range in json representation of schedule. Start time must be before end time: key: Sun, start: 14:00, end: 13:00');
        WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "13:00"}]}, "hours_in_advance": 24}');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testOneValidRangeWorks()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24}');

        $this->assertArrayHasKey('Sun', $weeklySchedule->daily());
        $forSunday = $weeklySchedule->forDay('Sun');
        $this->assertArrayHasKey('start', $forSunday[0]);
        $this->assertArrayHasKey('end', $forSunday[0]);
        $this->assertEquals('14:00', $forSunday[0]['start']);
        $this->assertEquals('15:00', $forSunday[0]['end']);
        $this->assertEquals(24, $weeklySchedule->hoursInAdvance());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testForDay()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24}');

        $this->assertArrayHasKey('Sun', $weeklySchedule->daily());
        $forSunday = $weeklySchedule->forDay('Sun');
        $this->assertArrayHasKey('start', $forSunday[0]);
        $this->assertArrayHasKey('end', $forSunday[0]);
        $this->assertEquals('14:00', $forSunday[0]['start']);
        $this->assertEquals('15:00', $forSunday[0]['end']);
        $this->assertEquals(24, $weeklySchedule->hoursInAdvance());
        $this->assertFalse($weeklySchedule->disableAll());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testForDayWhenDisableAll()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24, "disable_all": true}');

        $this->assertArrayHasKey('Sun', $weeklySchedule->daily());
        $forSunday = $weeklySchedule->forDay('Sun');
        $this->assertCount(0, $forSunday);
        $this->assertEquals(24, $weeklySchedule->hoursInAdvance());
        $this->assertTrue($weeklySchedule->disableAll());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testForDate()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24}');

        $this->assertArrayHasKey('Sun', $weeklySchedule->daily());
        $forSunday = $weeklySchedule->forDate(Carbon::parse('2020-06-14'));
        $this->assertArrayHasKey('start', $forSunday[0]);
        $this->assertArrayHasKey('end', $forSunday[0]);
        $this->assertEquals('14:00', $forSunday[0]['start']);
        $this->assertEquals('15:00', $forSunday[0]['end']);
        $this->assertEquals(24, $weeklySchedule->hoursInAdvance());
    }

    public function test_to_array()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24, "disable_all": false}');

        $this->assertEquals([
            'daily' => [
                'Sun' => [[
                    'start' => '14:00',
                    'end' => '15:00',
                ]],
            ],
            'hours_in_advance' => 24,
            'disable_all' => false,
        ], $weeklySchedule->toArray());
    }

    public function test_to_json()
    {
        $expected = '{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24, "disable_all": false}';
        $weeklySchedule = WeeklySchedule::fromJson($expected);

        $this->assertJsonStringEqualsJsonString($expected, $weeklySchedule->toJson());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testForDateDisableAll()
    {
        $weeklySchedule = WeeklySchedule::fromJson('{"daily": {"Sun":[{"start": "14:00", "end": "15:00"}]}, "hours_in_advance": 24, "disable_all": true}');

        $this->assertArrayHasKey('Sun', $weeklySchedule->daily());
        $forSunday = $weeklySchedule->forDate(Carbon::parse('2020-06-14'));
        $this->assertCount(0, $forSunday);
        $this->assertEquals(24, $weeklySchedule->hoursInAdvance());
        $this->assertTrue($weeklySchedule->disableAll());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testDefaultWorkingHoursParsesAsValid()
    {
        WeeklySchedule::fromArray(WeeklySchedule::defaultWorkingHours());
        $this->assertTrue(true);
    }

    private function buildJsonWithDailyKey(string $key, int $hours_in_advance)
    {
        $ret = array();
        if ($hours_in_advance) {
            $ret['hours_in_advance'] = $hours_in_advance;
        }
        if ($key) {
            $ret['daily'][$key] = [];
        }
        return json_encode($ret);
    }
}
