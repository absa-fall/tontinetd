<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <div class="container">
   <div class="row">
     <div class="col-md-6 "></div>
    <form action="/tour" method="post">
    @csrf
                 <input type="text" name="date_tour" placeholder="date_tour"><br><br>
                 <select name="etat">
                    <option value="en_attente">En attente</option>
                    <option value="terminer">Terminer</option>
                 </select>
             <button type="submit">SUBMIT</button>
        </form>
             </div>
    <div class="col-md-6">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">DateTour</th>
            <th scope="col">Etat</th>
          </tr>
        </thead>
        <tbody>
          @foreach ( $tours as $tour)
          
          <tr>
            <th scope="row">{{ $tour->id }}</th>
            <td>{{ $tour->date_tour }}</td>
              <td>{{ $tour->etat }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      
    </div>  
    </div>
   </div>
</body>
</html>