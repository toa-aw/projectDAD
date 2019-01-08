<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table class="table table-bordered">
      <tr>
        <td>
          {{$invoice->state}}
        </td>
        <td>
          {{$invoice->name}}
        </td>
      </tr>
      <tr>
        <td>
          {{$invoice->total_price}}
        </td>
        <td>
          {{$invoice->meal_id}}
        </td>
      </tr>
    </table>
  </body>
</html>