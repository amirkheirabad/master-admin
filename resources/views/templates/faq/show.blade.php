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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.faq-question').forEach(function (question) {
                question.addEventListener('click', function () {
                    const item = this.closest('.faq-item');
                    const answer = this.nextElementSibling;
                    const isOpen = item.classList.contains('open');

                    document.querySelectorAll('.faq-item.open').forEach(function (openItem) {
                        openItem.classList.remove('open');
                        openItem.querySelector('.faq-answer').style.maxHeight = null;
                    });

                    if (!isOpen) {
                        item.classList.add('open');
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <style>
        .faq-page-wrapper {
            padding: 0 8px;
        }

        .faq-header h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 24px 0;
        }

        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .x_panel{
            padding: 50px 0px 40px 0px;
        }
        .faq-item {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
            width: 65%;
            margin: auto;
        }

        .faq-item:hover {
            box-shadow: 0 2px 12px rgba(19, 60, 109, 0.08);
        }

        .faq-item.open {
            border-color: #133c6d;
            box-shadow: 0 2px 16px rgba(19, 60, 109, 0.13);
        }

        /* RTL: متن سمت راست، آیکون سمت چپ */
        .faq-question {
            padding: 18px 20px;
            cursor: pointer;
            display: flex;
            
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            user-select: none;
            transition: background 0.15s ease;
        }

        .faq-question:hover {
            background: #f8fafc;
        }

        .faq-item.open .faq-question {
            background: #edf2f9;
        }

        .faq-question h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.6;
            flex: 1;
            text-align: right;
        }

        .faq-item.open .faq-question h4 {
            color: #133c6d;
        }

        .faq-toggle {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e8eef6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 300;
            color: #133c6d;
            line-height: 1;
            transition: background 0.2s ease, transform 0.25s ease, color 0.2s ease;
        }

        .faq-item.open .faq-toggle {
            background: #133c6d;
            color: #ffffff;
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #f7fafd;
            border-top: 1px solid transparent;
        }

        .faq-item.open .faq-answer {
            border-top-color: #ccdaea;
        }

        .answer-content {
            padding: 18px 20px 20px;
            font-size: 14px;
            line-height: 1.9;
            color: #475569;
            text-align: justify;
            direction: rtl;
        }

        .answer-content p { margin-bottom: 10px; }
        .answer-content p:last-child { margin-bottom: 0; }

        .answer-content ul,
        .answer-content ol {
            margin: 10px 0;
            padding-right: 22px;
        }

        .answer-content li { margin-bottom: 6px; }
        .answer-content strong { color: #133c6d; }

        .answer-content a {
            color: #133c6d;
            text-decoration: none;
            border-bottom: 1px dashed #7ea8cc;
        }

        .answer-content a:hover {
            color: #0d2a4d;
        }

        .answer-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 12px 0;
        }

        .faq-pagination {
            margin-top: 24px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .faq-question {
                padding: 14px 16px;
            }

            .faq-question h4 {
                font-size: 14px;
            }

            .answer-content {
                padding: 14px 16px 16px;
                font-size: 13px;
            }

            .faq-toggle {
                width: 24px;
                height: 24px;
                font-size: 18px;
            }
        }
    </style>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="faq-page-wrapper">
                <div class="faq-header">
                    <h3>سوالات متداول</h3>
                </div>

                <div class="x_panel rounded-top p-3">
                    <div class="faq-list">
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h4>{{ $faq->question }}</h4>
                                    <span class="faq-toggle">+</span>
                                </div>
                                <div class="faq-answer">
                                    <div class="answer-content">
                                        {!! $faq->answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="faq-pagination">
                        {{ $faqs->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection