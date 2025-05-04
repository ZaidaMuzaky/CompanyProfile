@extends('layouts.app')

@section('title', 'HPMU Careers')

@section('content')

            <!-- Hero Section -->
            <div class="w-100"
                style="min-height:310px; background: url('{{ asset('assets/img/career1.jpg') }}'); background-size:cover; background-position:center; position:relative; display:flex; align-items:center; justify-content:center;">
                <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(28,21,18,0.28); z-index:0;">
                </div>
                <div style="position:absolute; bottom:30px; left:50px; z-index:1;">
                    <span class="fw-bold display-5 text-white px-5 py-3 rounded"
                        style="text-shadow:0 6px 16px #0007, 0 1px 0 #000;">
                        HCMGA
                    </span>
                </div>
            </div>


            <!-- Vision Section -->
            <div class="container my-5">
                <div class="row align-items-center bg-white shadow rounded p-4">
                    <div class="col-md-7">
                        <h2 class="text-center text-md-start fw-bold mb-3" style="letter-spacing:1px;">VISION</h2>
                        <p class="text-muted">To be a Strategic Partner to create Performing Work Life Balance Environment and to be
                            a good neighbour for community</p>
                        <p class="text-muted">To be the Strategic Partner in creating high-performing Work Life Balance Environment
                            that brings positive impact to the surrounding</p>
                        <p class="text-muted mb-0">People Excellence is competent individual of HPU that able to become change
                            agents that align with the culture and dynamic movement of the company that will lead to the great
                            performance of the company as a whole.</p>
                    </div>
                    <div class="col-md-5 text-center mt-4 mt-md-0">

                    </div>
                </div>
            </div>

            <!-- 3-Card Info Section Alternating -->
            <div class="container mb-5">
                @php
    $cards = [
        [
            'title' => 'Human Resource',
            'text' => 'Human Resource is the Company most valuable assets that become the driving force of the firm to achieve its philosophy, vision and mission in giving value for stakeholders, which are called as Sumber Daya Insani',
            'image' => asset('assets/img/career2.jpg'),
            'reverse' => false
        ],
        [
            'title' => 'Excellence in Harmony',
            'text' => 'Excellence in Harmony is the paradigm of every HPU individual to achieve superior performance within harmonious organizational life for the sustainability of company\'s business',
            'image' => asset('assets/img/career3.png'),
            'reverse' => true
        ],
        [
            'title' => 'Good Neighbour',
            'text' => 'Good Neighbour is HPU individual that aspires its surroundings internally or externally to create a good harmonious relationship for the sustainability of company\'s business as Sumber Daya Insani',
            'image' => 'https://hpu-mining.com/wp-content/uploads/2020/04/People_Excellence.png',
            'reverse' => false
        ],
    ];
                @endphp

                @foreach ($cards as $card)
                    <div
                        class="row align-items-center bg-white shadow-sm rounded p-4 mb-4 flex-column flex-md-row {{ $card['reverse'] ? 'flex-md-row-reverse' : '' }}">
                        <div class="col-md-7">
                            <h3 class="text-danger fw-bold mb-3">{{ $card['title'] }}</h3>
                            <p class="text-muted">{{ $card['text'] }}</p>
                        </div>
                        @if($card['image'])
                            <div class="col-md-5 text-center mt-3 mt-md-0">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
@endsection