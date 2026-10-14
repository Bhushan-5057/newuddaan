<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Remark</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->username }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ number_format($row->amount, 2) }}</td>
                <td>{{ $row->remark }}</td>
                <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y h:i A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
