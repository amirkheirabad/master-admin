<?php

namespace Modules\CustomerForm\Repositories;

use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormAssignment;
use Modules\CustomerForm\Models\FormQuestion;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Models\FormVersion;
use Modules\Stores\Models\Stores;

interface InterfaceCustomerForm
{
    public function createForm(array $data): Form;

    public function draft(Form $form): FormVersion;

    public function saveQuestion(Form $form, array $data, ?FormQuestion $question = null): FormQuestion;

    public function deleteQuestion(Form $form, FormQuestion $question): void;

    public function reorderQuestions(Form $form, array $questionIds): void;

    public function publish(Form $form): FormVersion;

    public function assign(Form $form, Stores $store): array;

    public function toggleAssignment(FormAssignment $assignment): FormAssignment;

    public function rotateToken(FormAssignment $assignment): string;

    public function upgradeAssignment(FormAssignment $assignment): FormAssignment;

    public function resolveAssignment(string $token): FormAssignment;

    public function submit(FormAssignment $assignment, array $answers): FormSubmission;
}
