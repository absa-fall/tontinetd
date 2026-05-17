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
     <div class="col-md-6 ">
    <form action="/cotisation" method="post">
    @csrf
        <input type="number" name="montant" placeholder="montant"><br><br>
          <input type="text" name="date_cotisation" placeholder="date_cotisation"><br><br>
          <select name="membre_id">
            @foreach ($membres as $membre)
                <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
            @endforeach
           </select><br><br>
           <button type="submit">SUBMIT</button>
        </form>
        </div>
         <div class="col-md-6">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Montant</th>
            <th scope="col">DateCotisation</th>
              <th scope="col">Membre</th>
          </tr>
        </thead>
        <tbody>
          @foreach ( $cotisations as $cotisation )
          
          <tr>
            <th scope="row">{{ $cotisation->id }}</th>
            <td>{{ $cotisation->montant }}</td>
            <td>{{ $cotisation->date_cotisation }}</td>
            <td>{{ $cotisation->membre->id }}</td>
          </tr>
          @endforeach
           </tbody>
      </table>
      
    </div>  
    </div>
   </div>
</body>
</html>