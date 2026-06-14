<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours</title>
</head>
<body>
   <div class="container">
   <div class="row">
     <div class="col-md-6">
    <form action="/tour" method="post">
    @csrf
        <select name="tontine_id">
            <option value="">-- Choisir une tontine --</option>
            @foreach($tontines as $tontine)
            <option value="{{ $tontine->id }}">{{ $tontine->nom }}</option>
            @endforeach
        </select><br><br>

        <select name="membre_id">
            <option value="">-- Choisir un membre --</option>
            @foreach($membres as $membre)
            <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
            @endforeach
        </select><br><br>

        <input type="date" name="date_tour" placeholder="date_tour"><br><br>
        <select name="etat">
            <option value="en_attente">En attente</option>
            <option value="terminer">Terminer</option>
        </select><br><br>
        <button type="submit">SUBMIT</button>
    </form>

    @if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
    @endif
     </div>
    <div class="col-md-6">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Tontine</th>
            <th scope="col">DateTour</th>
            <th scope="col">Etat</th>
            <th scope="col">Bénéficiaire</th>
            <th scope="col">Mode réception</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($tours as $tour)
          <tr>
            <th scope="row">{{ $tour->id }}</th>
            <td>{{ $tour->tontine->nom ?? '-' }}</td>
            <td>{{ $tour->date_tour }}</td>
            <td>{{ $tour->etat }}</td>
            <td>{{ $tour->membre->nom ?? '-' }} {{ $tour->membre->prenom ?? '' }}</td>
            <td>{{ $tour->mode_reception ?? 'En attente de réponse' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>  
    </div>
   </div>
</body>
</html>