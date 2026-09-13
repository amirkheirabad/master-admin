<?php

namespace Tests\Feature\CustomerForm;

use Database\Seeders\CustomerFormSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CustomerForm\Models\Form;
use Tests\TestCase;

class CustomerFormSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_two_published_forms_without_mutating_them_on_rerun(): void
    {
        $this->seed(CustomerFormSeeder::class);
        $form = Form::where('title', 'فرم اطلاعات اولیه مشتری')->firstOrFail();
        $versionId = $form->published_version_id;

        $this->seed(CustomerFormSeeder::class);

        $this->assertSame(2, Form::count());
        $this->assertSame($versionId, $form->fresh()->published_version_id);
        $this->assertSame(12, $form->publishedVersion->questions()->count());
        $this->assertNull($form->fresh()->draft_version_id);
    }

    public function test_seeded_forms_contain_the_approved_questions_and_options(): void
    {
        $this->seed(CustomerFormSeeder::class);

        $customer = Form::where('title', 'فرم اطلاعات اولیه مشتری')->firstOrFail();
        $gatewayQuestion = $customer->publishedVersion->questions()->where('sort_order', 7)->firstOrFail();
        $this->assertSame('اگر درگاه دارید، از کدوم‌ها استفاده می‌کنید؟', $gatewayQuestion->label);
        $this->assertSame(
            ['درگاه مستقیم بانکی', 'زرین‌پال', 'زیبال', 'آیدی‌پی', 'نکست‌پی', 'هنوز انتخاب نکرده‌ام'],
            $gatewayQuestion->options()->pluck('label')->all()
        );

        $design = Form::where('title', 'فرم طراحی گرافیک دیزاین')->firstOrFail();
        $this->assertSame(12, $design->publishedVersion->questions()->count());
        $this->assertSame(
            ['ساده و مینیمال', 'مدرن', 'رسمی', 'صمیمی و پرانرژی', 'لوکس', 'ترجیح مشخصی ندارم'],
            $design->publishedVersion->questions()->where('sort_order', 8)->firstOrFail()->options()->pluck('label')->all()
        );
    }
}
