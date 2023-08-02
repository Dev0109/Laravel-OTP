<html>
  <head>
    <style>
      table, td, th {
        border: 1px solid;
      }

      table {
        border-collapse: collapse;
      }

      .title {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
    </style>
  </head>

  <body>
    <div class="title">
      <h3>View Price List Comparation</h3>
    </div>
    <table>
      <thead>
        <tr>
          <th rowspan="2">ID</th>
          <th rowspan="2">ITEMCODE</th>
          <th rowspan="2">DESCRIPTION</th>
          <th rowspan="2">PRICE</th>
          @foreach($pricecompetitors as $key => $pricecompetitor)
            <th colspan="2">
              {{ $pricecompetitor->name }}
            </th>
          @endforeach
        </tr>
        <tr>
          @foreach($pricecompetitors as $key => $pricecompetitor)
            <th style="text-align: center;">
              DESCRIPTION
            </th>
            <th style="text-align: center;">
              PRICE
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($prices as $key => $price)
          <tr data-entry-id="{{ $price->id }}">
            <td align="center">
              {{ $loop->index + 1 }}
            </td>
            <td align="center">
              {{ $price->itemcode ?? '' }}
            </td>
            <td align="center">
              {{ $price->description ?? '' }}
            </td>
            <td align="center">
              {{ number_format($price->price, 2) ?? '' }} €
            </td>
            @php
              $num_var = 0;
            @endphp
            @foreach($pricecompetitors as $keys => $pricecompetitor)
              @php
                $compares_num = 0;
              @endphp
              @foreach($pricecompares as $keyss => $pricecompare)
                @php
                  $compares_num ++;
                @endphp
                @if($pricecompare->user_id == auth()->user()->id && $pricecompare->price_id == $price->id && $pricecompare->competitor_id == $pricecompetitor->id)
                  @php
                    $num_var ++;
                    $compares_num --;
                  @endphp
                  <td align="center">
                    {{ $pricecompare->description }}
                  </td>
                  <td align="center">
                    {{ number_format($pricecompare->price, 2) ?? '' }} €
                  </td>
                @endif
              @endforeach
              @if($compares_num == $pricecompares->count())
                <td align="center"></td>
                <td align="center"></td>
              @endif
            @endforeach
          </tr>
        @endforeach
      <tbody>
    </table>
  </body>
</html>