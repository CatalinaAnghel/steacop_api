<?php

namespace App\ApiResource;

class Grades
{
    private StudentData $studentData;

    private float $milestoneMeetingsGrade;

    private float $assignmentsGrade;

    private float $totalGrade;

    /**
     * @return StudentData
     */
    public function getStudentData(): StudentData
    {
        return $this->studentData;
    }

    /**
     * @param StudentData $studentData
     * @return Grades
     */
    public function setStudentData(StudentData $studentData): Grades
    {
        $this->studentData = $studentData;
        return $this;
    }

    /**
     * @return float
     */
    public function getMilestoneMeetingsGrade(): float
    {
        return $this->milestoneMeetingsGrade;
    }

    /**
     * @param float $milestoneMeetingsGrade
     * @return Grades
     */
    public function setMilestoneMeetingsGrade(float $milestoneMeetingsGrade): Grades
    {
        $this->milestoneMeetingsGrade = $milestoneMeetingsGrade;
        return $this;
    }

    /**
     * @return float
     */
    public function getAssignmentsGrade(): float
    {
        return $this->assignmentsGrade;
    }

    /**
     * @param float $assignmentsGrade
     * @return Grades
     */
    public function setAssignmentsGrade(float $assignmentsGrade): Grades
    {
        $this->assignmentsGrade = $assignmentsGrade;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalGrade(): float
    {
        return $this->totalGrade;
    }

    /**
     * @param float $totalGrade
     * @return Grades
     */
    public function setTotalGrade(float $totalGrade): Grades
    {
        $this->totalGrade = $totalGrade;
        return $this;
    }
}
