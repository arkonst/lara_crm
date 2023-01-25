<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="card p-6">
                            @if ($company['logo_path'])
                                <img class="card-img-top" src="/storage/logos/{{ $company['logo_path'] }}" alt="Logo" style="width: 100px;">
                            @else
                                <img class="card-img-top" src="http://dummyimage.com/100" alt="Logo" style="width: 100px;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $company['name'] }}</h5>
                                <p class="card-text" id="email"><strong>Email:</strong> {{ $company['email'] }}</p>
                                <p class="card-text" id="address"><strong>Address:</strong> {{ $company['address'] }}</p>
                                <div id="map" style="width: 100%; height: 300px"></div>
                                <p class="card-text" id="email"><strong>Сотрудники:</strong></p>
                                @foreach($company['employees'] as $employee)
                                    <p class="card-text" id="address">{{ $employee['name'] }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=fd8468b3-e1b0-458f-8909-950cdaf7cfb1&lang=ru_RU"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        function init(){
            let address = '{{ $company['address'] }}'

            let myMap = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 7
            });
            ymaps.geocode(address, {results: 1}).then(function (res) {
                let firstGeoObject = res.geoObjects.get(0),
                    coords = firstGeoObject.geometry.getCoordinates(),
                    bounds = firstGeoObject.properties.get('boundedBy');
                myMap.geoObjects.add(firstGeoObject);
                myMap.setBounds(bounds, {
                    checkZoomRange: true
                });
            });
        }
    </script>
</x-app-layout>
