<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\CustomerForm\Models\Form;

class CustomerFormSeeder extends Seeder
{
    public function run(): void
    {
        $this->createForm('فرم اطلاعات اولیه مشتری', [
            ['boolean', true, 'فروشگاه‌تون الان سایت فعال داره؟'],
            ['text', false, 'اگر سایت دارید، آدرسش رو اینجا بنویسید.'],
            ['boolean', true, 'دامنه‌ای که می‌خواهید استفاده کنید آماده است؟'],
            ['text', false, 'اگر دامنه دارید، آدرس دامنه چیه؟'],
            ['boolean', true, 'برای کسب‌وکارتون اینماد فعال دارید؟'],
            ['boolean', true, 'الان درگاه پرداخت فعال دارید؟'],
            ['checkbox', false, 'اگر درگاه دارید، از کدوم‌ها استفاده می‌کنید؟', ['درگاه مستقیم بانکی', 'زرین‌پال', 'زیبال', 'آیدی‌پی', 'نکست‌پی', 'هنوز انتخاب نکرده‌ام']],
            ['checkbox', false, 'دوست دارید فروشگاه‌تون به کدوم سرویس‌ها وصل شود؟', ['ترب', 'ایمالز', 'دیجی‌پی', 'اسنپ‌پی', 'شاپینو', 'فعلاً مطمئن نیستم']],
            ['select', true, 'چطور با ما آشنا شدید؟', ['معرفی دوستان یا مشتریان', 'اینستاگرام', 'جست‌وجوی گوگل', 'تماس یا پیام همکاران ما', 'سایر']],
            ['textarea', false, 'اگر از یک پلتفرم دیگه میاید، مهم‌ترین دلیل تغییرتون چیه؟'],
            ['textarea', true, 'مهم‌ترین انتظارتون از سایت جدید چیه؟'],
            ['textarea', false, 'نکته دیگه‌ای هست که بهتره قبل از شروع بدونیم؟'],
        ]);

        $this->createForm('فرم طراحی گرافیک دیزاین', [
            ['checkbox', true, 'دقیقاً چه چیزی نیاز دارید طراحی شود؟', ['لوگو', 'هویت بصری', 'بنر سایت', 'قالب پست یا استوری', 'کاتالوگ یا بروشور', 'بسته‌بندی', 'سایر']],
            ['textarea', true, 'این طرح قرار است کجا استفاده شود؟'],
            ['textarea', true, 'هدف اصلی این طراحی چیه؟'],
            ['textarea', true, 'مخاطب اصلی این طرح چه کسانی هستند؟'],
            ['textarea', false, 'چه متن‌ها یا اطلاعاتی باید داخل طرح قرار بگیرد؟'],
            ['text', false, 'اگر لوگو یا فایل هویت بصری دارید، لینک دریافتش را بفرستید.'],
            ['textarea', false, 'چه رنگ‌هایی را ترجیح می‌دهید یا نمی‌خواهید استفاده شوند؟'],
            ['radio', true, 'کدوم سبک به چیزی که می‌خواهید نزدیک‌تره؟', ['ساده و مینیمال', 'مدرن', 'رسمی', 'صمیمی و پرانرژی', 'لوکس', 'ترجیح مشخصی ندارم']],
            ['textarea', false, 'اگر نمونه‌ای دوست دارید، لینک یا توضیحش را بفرستید.'],
            ['text', false, 'ابعاد یا خروجی موردنیاز را می‌دانید؟'],
            ['text', true, 'چه زمانی به طرح نهایی نیاز دارید؟'],
            ['textarea', false, 'نکته دیگه‌ای هست که طراح باید بداند؟'],
        ]);
    }

    private function createForm(string $title, array $questions): void
    {
        if (Form::where('title', $title)->exists()) {
            return;
        }

        DB::transaction(function () use ($title, $questions) {
            $form = Form::create(['title' => $title, 'is_active' => true]);
            $version = $form->versions()->create(['version_number' => 1]);

            foreach ($questions as $questionOrder => $definition) {
                [$type, $required, $label] = $definition;
                $question = $version->questions()->create([
                    'label' => $label,
                    'type' => $type,
                    'is_required' => $required,
                    'sort_order' => $questionOrder + 1,
                ]);

                foreach ($definition[3] ?? [] as $optionOrder => $option) {
                    $question->options()->create(['label' => $option, 'sort_order' => $optionOrder + 1]);
                }
            }

            $version->update(['published_at' => now()]);
            $form->update(['published_version_id' => $version->id]);
        });
    }
}
