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
    <form action="/tontine" method="post">
    @csrf
        <input type="text" name="nom" placeholder="nom"><br><br>
          <input type="text" name="description" placeholder="description"><br><br>
           <input type="number" name="montant" placeholder="montant"><br><br>
               <input type="text" name="date_debut" placeholder="date_debut"><br><br>
                 <input type="text" name="date_fin" placeholder="date_fin"><br><br>
                 <select name="frequence">
                    <option value="semaine">Semaine</option>
                    <option value="mensuelle">Mensuelle</option>
                    <option value="journaler">journalier</option>
                 </select>
             <button type="submit">SUBMIT</button>
        </form>
         </div>
    <div class="col-md-6">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nom</th>
            <th scope="col">Description</th>
            <th scope="col">Montant</th>
            <th scope="col">DateDebut</th>
            <th scope="col">DateFin</th>
            <th scope="col">Frequence</th>
          </tr>
        </thead>
        <tbody>
          @foreach ( $tontines as $tontine)
          
          <tr>
            <th scope="row">{{ $tontine->id }}</th>
            <td>{{ $tontine->nom }}</td>
            <td>{{ $tontine->description }}</td>
            <td>{{ $tontine->montant }}</td>
            <td>{{ $tontine->date_debut }}</td>
            <td>{{ $tontine->date_fin}}</td>
            <td>{{ $tontine->frequence }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      
    </div>  
    </div>
   </div>
</body>
</html>