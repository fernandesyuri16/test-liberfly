<?php

namespace App\Services;

use App\Helpers\Http;
use App\Repositories\HolidayPlanRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class HolidayPlanService
{
    use Http;

    private HolidayPlanRepository $repository;

    public function __construct()
    {
        $this->repository = new HolidayPlanRepository;
    }

    /**
     * Create a new holiday plan.
     *
     * @param array $holidayPlanDetails - Details of the holiday plan.
     * @return array - Returns an array with the created holiday plan or an error message.
     */
    public function createHolidayPlan(array $holidayPlanDetails): array
    {
        $holidayPlanDetails['user_id'] = auth()->user()->id;

        $holidayPlan = $this->repository->createHolidayPlan($holidayPlanDetails);

        return $this->created($holidayPlan);
    }

    /**
     * Get a specific holiday plan.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @return array - Returns an array with the holiday plan or an error message.
     */
    public function getHolidayPlan(int $holidayId): array
    {
        $error = $this->checkIfHasError($holidayId);

        if (! empty($error)) {
            return $error;
        }

        $holidayPlan = $this->repository->getHolidayPlan($holidayId);

        return $this->ok($holidayPlan);
    }

    /**
     * Get all holiday plans.
     *
     * @return array - Returns an array with all holiday plans.
     */
    public function getHolidayPlans(): array
    {
        $holidayPlans = $this->repository->getHolidayPlans();

        return $this->ok($holidayPlans->items());
    }

    /**
     * Check if there are any errors.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @param bool $checkPermission - Whether to check for permissions.
     * @return array - Returns an array with an error message if there are any errors.
     */
    private function checkIfHasError(int $holidayId, bool $checkPermission = false): array
    {
        $holidayPlanDetails = $this->repository->getHolidayPlan($holidayId);

        if (! $this->holidayPlanExists($holidayId)) {
            return $this->notFound("Holiday plan doesn't exists.");
        }

        if ($checkPermission && $holidayPlanDetails['user_id'] !== auth()->user()->id) {
            return $this->forbidden("You don't have permission to perform this action.");
        }

        return [];
    }

    /**
     * Check if a holiday plan exists.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @return bool - Returns true if the holiday plan exists, false otherwise.
     */
    private function holidayPlanExists(int $holidayId): bool
    {
        $holiday = $this->repository->getHolidayPlan($holidayId);

        if (empty($holiday->id)) {
            return false;
        }

        return true;
    }

    /**
     * Update a specific holiday plan.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @param array $holidayPlanDetails - The new details of the holiday plan.
     * @return array - Returns an array with the updated holiday plan or an error message.
     */
    public function updateHolidayPlan(int $holidayId, array $holidayPlanDetails): array
    {
        $error = $this->checkIfHasError($holidayId, true);

        if (! empty($error)) {
            return $error;
        }

        $this->repository->updateHolidayPlan($holidayId, $holidayPlanDetails);

        $holidayPlan = $this->repository->getHolidayPlan($holidayId);

        return $this->ok($holidayPlan);
    }

    /**
     * Delete a specific holiday plan.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @return array - Returns an array with a success message or an error message.
     */
    public function deleteHolidayPlan(int $holidayId): array
    {
        $error = $this->checkIfHasError($holidayId, true);

        if (! empty($error)) {
            return $error;
        }

        $this->repository->deleteHolidayPlan($holidayId);

        return $this->ok('Holiday plan successfully deleted!');
    }

    /**
     * Generate a PDF for a specific holiday plan.
     *
     * @param int $holidayId - The ID of the holiday plan.
     * @return mixed - Returns the PDF file for download or an error message.
     */
    public function generatePdf(int $holidayId): mixed
    {
        $error = $this->checkIfHasError($holidayId);

        if (! empty($error)) {
            return $error;
        }

        $holidayPlan = $this->repository->getHolidayPlan($holidayId);

        $viewData = $holidayPlan->toArray();

        $viewData['userName'] = $holidayPlan->user->name;

        $html = view('holiday-plan-pdf', compact('viewData'))->render();

        $pdf = Pdf::loadHTML($html);

        return $pdf->download('holiday-plan-pdf');
    }
}
