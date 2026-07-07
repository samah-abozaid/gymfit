<?php

use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    private function makeCourse(): Course
    {
        return new Course(
            name:      'Yoga du matin',
            type:      'yoga',
            level:     'debutant',
            coach:     'Sophie Martin',
            day:       'lundi',
            startTime: '09:00',
            endTime:   '10:00'
        );
    }

    public function testConstructorAssignsRequiredFields(): void
    {
        $course = $this->makeCourse();

        $this->assertSame('Yoga du matin', $course->getName());
        $this->assertSame('yoga', $course->getType());
        $this->assertSame('debutant', $course->getLevel());
        $this->assertSame('Sophie Martin', $course->getCoach());
        $this->assertSame('lundi', $course->getDay());
        $this->assertSame('09:00', $course->getStartTime());
        $this->assertSame('10:00', $course->getEndTime());
    }

    public function testDefaultMaxCapacityIsTwenty(): void
    {
        $this->assertSame(20, $this->makeCourse()->getMaxCapacity());
    }

    public function testDefaultIdIsNull(): void
    {
        $this->assertNull($this->makeCourse()->getId());
    }

    public function testSettersUpdateValues(): void
    {
        $course = $this->makeCourse();

        $course->setId(7);
        $course->setName('CrossFit intensif');
        $course->setType('crossfit');
        $course->setLevel('avance');
        $course->setCoach('Karim Benali');
        $course->setDay('mercredi');
        $course->setStartTime('18:00');
        $course->setEndTime('19:30');
        $course->setMaxCapacity(15);

        $this->assertSame(7, $course->getId());
        $this->assertSame('CrossFit intensif', $course->getName());
        $this->assertSame('crossfit', $course->getType());
        $this->assertSame('avance', $course->getLevel());
        $this->assertSame('Karim Benali', $course->getCoach());
        $this->assertSame('mercredi', $course->getDay());
        $this->assertSame('18:00', $course->getStartTime());
        $this->assertSame('19:30', $course->getEndTime());
        $this->assertSame(15, $course->getMaxCapacity());
    }
}
