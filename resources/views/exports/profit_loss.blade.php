<table>
    <thead>
        <tr>
            <th>Category</th>
            @foreach($report['months'] as $month)
                <th>{{ \Carbon\Carbon::create()->month($month)->format('M') }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report['data'] as $category => $row)
            <tr>
                <td>{{ $category }}</td>
                @foreach($report['months'] as $month)
                    <td>{{ $row[$month] ?? 0 }}</td>
                @endforeach
                <td>{{ $row['total'] }}</td>
            </tr>
        @endforeach

        <tr>
            <td><b>Total Income</b></td>
            @foreach($report['months'] as $month)
                <td>{{ $report['summary']['income'][$month] ?? 0 }}</td>
            @endforeach
            <td>{{ $report['summary']['income']['total'] }}</td>
        </tr>

        <tr>
            <td><b>Total Expense</b></td>
            @foreach($report['months'] as $month)
                <td>{{ $report['summary']['expense'][$month] ?? 0 }}</td>
            @endforeach
            <td>{{ $report['summary']['expense']['total'] }}</td>
        </tr>

        <tr>
            <td><b>Net Income</b></td>
            @foreach($report['months'] as $month)
                <td>{{ $report['summary']['net'][$month] ?? 0 }}</td>
            @endforeach
            <td>{{ $report['summary']['net']['total'] }}</td>
        </tr>
    </tbody>
</table>
