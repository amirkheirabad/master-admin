@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <style>
        /* فونت و تنظیمات پایه */
        .faq-list {
            max-width: 900px;
            margin: 20px auto;
            font-family: 'Vazir', 'IRANSans', 'Tahoma', sans-serif;
        }

        .faq-item {
            background: #ffffff;
            border-radius: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }


        .faq-question {
            padding: 18px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #f5f6f8 100%);
        }

        .faq-question h4 {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.5;
            flex: 1;
            letter-spacing: -0.2px;
        }

        .faq-icon {
            position: relative;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: black;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .faq-question.active .faq-icon {
            transform: rotate(45deg) scale(1.1);
        }

        /* بخش پاسخ با انیمیشن */
        .faq-answer {
            background: linear-gradient(135deg, #f8f9fe 0%, #ffffff 100%);
            border-top: 1px solid rgba(102, 126, 234, 0.1);
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        .answer-content {
            padding: 20px 24px;
            line-height: 1.8;
            color: #2d3436;
            font-size: 15px;
            text-align: justify;
        }

        /* استایل برای محتوای HTML داخل پاسخ */
        .answer-content p {
            margin-bottom: 12px;
        }

        .answer-content ul,
        .answer-content ol {
            margin: 12px 0;
            padding-right: 24px;
        }

        .answer-content li {
            margin-bottom: 8px;
        }

        .answer-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 16px 0;
        }

        .answer-content strong {
            color: #667eea;
        }

        .answer-content a {
            color: #667eea;
            text-decoration: none;
            border-bottom: 1px dashed #667eea;
        }

        .answer-content a:hover {
            color: #764ba2;
            border-bottom-color: #764ba2;
        }

        /* اسکرول بار */
        .faq-answer {
            max-height: 600px;
            overflow-y: auto;
        }

        .faq-answer::-webkit-scrollbar {
            width: 6px;
        }

        .faq-answer::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 10px;
        }

        .faq-answer::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        /* ریسپانسیو برای موبایل */
        @media (max-width: 768px) {
            .faq-list {
                margin: 16px;
            }

            .faq-question {
                padding: 14px 18px;
            }

            .faq-question h4 {
                font-size: 15px;
            }

            .answer-content {
                padding: 16px 20px;
                font-size: 14px;
            }
        }

        /* دکوراسیون اضافه */
        .faq-item:first-child {
            border-top: 1px solid rgba(102, 126, 234, 0.2);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(function(question) {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;

                    // تغییر کلاس active
                    this.classList.toggle('active');

                    // باز/بستن جواب
                    if (answer.style.display === 'none' || answer.style.display === '') {
                        answer.style.display = 'block';
                    } else {
                        answer.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>سوالات متداول</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-top">
                    <div class="faq-list">
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item">
                                <div class="faq-question" data-id="{{ $index }}">
                                    <h4>{{ $faq->question }}</h4>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer" id="answer-{{ $index }}" style="display: none;">
                                    <div class="answer-content">
                                        {!! $faq->answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
