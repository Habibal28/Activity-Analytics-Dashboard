<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Activity & Analytics') }}
        </h2>
    </x-slot> --}}

    <!-- CONTENT -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end" id="filter-form">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Action</label>
                        <select name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Actions</option>
                            @foreach ($masterActivity as $action)
                            <option>{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700"
                            id="btn-apply">
                            Apply
                        </button>
                    </div>
                </form>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total Aktivitas</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="total-activity">{{ $totalActivity }}</h3>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">User Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="total-user-active">{{ $totalUserActive }}</h3>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Rata-rata / Hari</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="avg-activity-per-day">{{ $avgActivityPerDay }}</h3>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Top Action</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="most-activity">{{$mostActivity}}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h4 class="font-semibold text-gray-700 mb-4">Aktivitas Per Hari</h4>
                <canvas id="activityPerDayChart" height="90"></canvas>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-gray-700 mb-4">Aktivitas Berdasarkan Action</h4>
                    <canvas id="activityByActionChart"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-gray-700 mb-4">Top 5 User Teraktif</h4>
                    <canvas id="topUsersChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        let activityChart, actionChart, topUserChart;
        // LINE Chart
        activityChart = new Chart(document.getElementById('activityPerDayChart'), {
            type: 'line',
            data: {
                labels: @json($dailyLabel),
                datasets: [{
                    data: {{ json_encode($dailyTotal)}},
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                responsive: true
            }
        });

        // ACTIVITY Chart
        actionChart =  new Chart(document.getElementById('activityByActionChart'), {
            type: 'bar',
            data: {
                labels: @json($perActionLabel),
                datasets: [{
                    data: {{json_encode($perActionTotal)}}
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                responsive: true
            }
        });

        // MOST ACTIVE USER Chart
        topUserChart =  new Chart(document.getElementById('topUsersChart'), {
            type: 'bar',
            data: {
                labels: @json($topUserLabel),
                datasets: [{
                    data: {{json_encode($topUserTotal)}}
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                responsive: true
            }
        });
    </script>

    <script>
        $(function () {
    
            $('#filter-form').on('submit', function (e) {
 
            e.preventDefault();

            const startDate = $('input[name="start_date"]').val();
            const endDate   = $('input[name="end_date"]').val();
            const action    = $('select[name="action"]').val();
    
            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                dataType: "json",
                data: {
                    start_date: startDate || null,
                    end_date: endDate || null,
                    action: action || null
                },
                beforeSend: function () {
                    $('#btn-apply')
                        .text('Loading...')
                        .prop('disabled', true);
                },
                success: function (res) {
    
                    $('#total-activity').text(res.totalActivity);
                    $('#total-user-active').text(res.totalUserActive);
                    $('#avg-activity-per-day').text(res.avgActivityPerDay);
                    $('#most-activity').text(res.mostActivity ?? '-');
                    
    
                    activityChart.data.labels = res.dailyLabel;
                    activityChart.data.datasets[0].data = res.dailyTotal;
                    activityChart.update();
    
                    actionChart.data.labels = res.perActionLabel;
                    actionChart.data.datasets[0].data = res.perActionTotal;
                    actionChart.update();
    
                    topUserChart.data.labels = res.topUserLabel;
                    topUserChart.data.datasets[0].data = res.topUserTotal;
                    topUserChart.update();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message ?? 'Terjadi kesalahan');
                },
                complete: function () {
                    $('#btn-apply')
                        .text('Apply')
                        .prop('disabled', false);
                }
            });
    
        });
    
    });
    </script>

</x-app-layout>