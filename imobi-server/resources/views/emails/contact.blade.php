<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <style>
      .linkReset{
        height: 40px; 
        width: 120px;
        background-color: #1771db;
        display: flex;
        align-items: center !important;
        justify-content: center !important;
        color: white !important;
        text-decoration: none;
        border-radius: 15px;
        text-transform: uppercase;
        padding: 11px;
        box-sizing: border-box;
      }
    </style>

  </head>
  <body {{-- @style(['width: 1780px',
                'height: 800px',
                'background-color: #1771db',
                'display: flex',
                'justify-content: center',
                'align-items: center']) --}}>
    <div {{-- @style([ 'width: 800px',
                  'height: 400px',
                  'background-color: white',
                  'display: flex',
                  'flex-direction: column',
                  'align-items: center',
                  'justify-content: center']) --}}>

      <h1 @style(['color: #1771db'])>Bonjour, {{ $contact['name'] }}</h1>

      <h2 @style(['color: red'])>Réinitialisation de mot de passe</h2>

      <p>Si vous avez fais une demande de réinitialisation appuyez:</p>

      <a class="linkReset" href="http://localhost:5173/resetPassword?{{$contact['email']}}?{{$contact['key']}}" target="_blank" rel="noopener noreferrer">reinitialiser</a>
   
    </div>
    
  </body>
</html>