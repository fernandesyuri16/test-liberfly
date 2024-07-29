<?php

namespace App\Interfaces;

interface HolidayPlanRepositoryInterface
{
    public function createHolidayPlan(array $holidayPlanDetails);
    public function getHolidayPlan(int $holidayPlanId);
    public function getHolidayPlans();
    public function updateHolidayPlan(int $holidayPlanId, array $holidayPlanDetails);
    public function deleteHolidayPlan(int $holidayPlanId);
}
