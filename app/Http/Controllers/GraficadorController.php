<?php



namespace App\Http\Controllers;

use App\Models\Graficador;

use Illuminate\Support\Facades\DB; // <---- Agrega esto arriba en el controlador
use Aws\Rekognition\RekognitionClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client; // Solo si usas Guzzle, opcional
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;


class GraficadorController extends Controller
{
    // Mostrar el listado de registros
    public function index()
    {
        //$graficadores = Graficador::all();  // Obtiene todos los registros
        //$graficadores = DB::table('graficador')->get(); // Consulta directa a la tabla "graficador"

        $user = auth()->user(); // Obtener el usuario autenticado

$graficadores = DB::table('graficador')
    ->join('graficadorusuario', 'graficador.IdGraficador', '=', 'graficadorusuario.IdGraficador')
    ->where('graficadorusuario.IdUser', $user->id)
    ->select('graficador.*') // Trae todos los campos del graficador
    ->get();

        //print_r($graficadores);
        return view('graficadores.index', compact('graficadores'));
    }

    // Mostrar el formulario para crear un nuevo registro
    public function create()
    {
        return view('graficadores.create');
    }

    // Guardar el nuevo registro en la base de datos
    public function store(Request $request)
{
    // Validar los datos recibidos, incluyendo los nuevos campos
    $request->validate([
        'Titulo' => 'required|string|max:255',
        'Descripcion' => 'required|string',
        'FechaCreacion' => 'required|date',
        'HoraCreacion' => 'required',
        'Estado' => 'required|integer',
        'Contenido' => 'required|string',
    ]);

    // Insertar en la base de datos usando DB::table()
    
    $idGraficador=DB::table('graficador')->insertGetId([
    
        'Titulo' => $request->Titulo,
        'Descripcion' => $request->Descripcion,
        'FechaCreacion' => $request->FechaCreacion,
        'HoraCreacion' => $request->HoraCreacion,
        'Estado' => $request->Estado,
        'Contenido' => $request->Contenido,
        'created_at' => now(), // Fecha de creación automática
        'updated_at' => now(), // Fecha de actualización automática
    ]);

    $user = auth()->user(); // Asegúrate de que el usuario esté autenticado

        // Insertar el nuevo colaborador en la tabla graficadorusuario
        $result = DB::table('graficadorusuario')->insert([
            'IdGraficador' => $idGraficador,
            'Contador' => 1,  // Ajusta esto si necesitas contar los colaboradores
            'IdUser' => $user->id,
            'TipoUsuario' => 1,  // O el tipo que corresponda
            'Fecha' => now()->toDateString(),
            'Hora' => now()->toTimeString(),
            'Estado' => 2,  // O el estado que sea necesario
        ]);


    // Redirigir al listado con un mensaje de éxito
    return redirect()->route('graficadores.index')->with('success', 'Graficador creado y asociado exitosamente.');


    // Redireccionar de nuevo al listado con mensaje (opcional)
    //return redirect()->route('graficadores.index')->with('success', 'Graficador creado exitosamente.');
}


    // Mostrar el formulario para editar un registro
    public function edit($idGraficador)
    {
        //$graficador = Graficador::findOrFail($idGraficador);
        $graficador = DB::table('graficador')->where('IdGraficador', $idGraficador)->first();
        return view('graficadores.edit', compact('graficador'));
    }

    // Actualizar el registro en la base de datos
    public function update(Request $request, $id)
    {
      

        $graficador = Graficador::findOrFail($id);
        $graficador->update($request->all());  // Actualiza el registro

        return redirect()->route('graficadores.index');  // Redirige a la lista de registros
    }

    // Eliminar un registro
    public function destroy($id)
    {
        $graficador = Graficador::findOrFail($id);
        $graficador->delete();  // Elimina el registro

        return redirect()->route('graficadores.index');  // Redirige a la lista de registros
    }

    public function listarUsuariosGraficadores()
{
    $usuarios = DB::table('users')
        ->join('graficadorusuario', 'users.id', '=', 'graficadorusuario.IdUser')
        ->select('users.*')
        ->distinct()
        ->get();

    return view('graficadores.ListaColaboradores', compact('usuarios'));
}
public function listarUsuariosPorGraficador($idGraficador)
{
    $usuarios = DB::table('users')
        ->join('graficadorusuario', 'users.id', '=', 'graficadorusuario.IdUser')
        ->where('graficadorusuario.IdGraficador', $idGraficador)
        ->select('users.*','graficadorusuario.IdGraficador', 'graficadorusuario.Estado as estadoGraficador')
        ->distinct()
        ->get();

    return view('graficadores.ListaColaboradores', compact('usuarios', 'idGraficador'));
}

public function cambiarEstado($idGraficador, $id)
{
    // Opcional: Validar que el graficador corresponda al usuario
    $registro = DB::table('graficadorusuario')
                ->where('IdGraficador', $idGraficador)
                ->where('IdUser', $id)
                ->first();

    if (!$registro) {
        return redirect()->back()->with('error', 'Registro no encontrado.');
    }

    // Actualizar el estado
    DB::table('graficadorusuario')
    ->where('IdGraficador', $idGraficador)
    ->where('IdUser', $id)
        ->update([
            'Estado' => 2,
        ]);

    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
}
    // Método que lista todas las gráficas
    public function listatodaslasgraficas()
    {
        // Obtener todos los registros de la tabla graficador
        $graficadores = Graficador::all();

        // Pasar los datos a la vista
        return view('graficadores.listatodaslasgraficas', compact('graficadores'));
    }

    // Método para añadir colaborador
    /*public function addCollaborator(Request $request)
    {


        // Obtener los datos del usuario autenticado
        $user = auth()->user(); // Asegúrate de que el usuario esté autenticado

        // Insertar el nuevo colaborador en la tabla graficadorusuario
        $result = DB::table('graficadorusuario')->insert([
            'IdGraficador' => $request->graficadorId,
            'Contador' => 1,  // Ajusta esto si necesitas contar los colaboradores
            'IdUser' => $user->id,
            'TipoUsuario' => 2,  // O el tipo que corresponda
            'Fecha' => now()->toDateString(),
            'Hora' => now()->toTimeString(),
            'Estado' => 1,  // O el estado que sea necesario
        ]);

        // Responder con un JSON
        return response()->json(['success' => $result]);
    }*/
    
    public function addCollaborator(Request $request)
    {
        $user = auth()->user(); // Asegúrate de que el usuario esté autenticado
    
        // Verificar que el usuario no sea el creador del graficador
        $graficador = DB::table('graficadorusuario')->where('IdGraficador', $request->graficadorId)->first();
    
        if ($graficador && $graficador->IdUser === $user->id) {
            return response()->json(['error' => 'No puedes enviarte una invitación a ti mismo.'], 400);
        }
    
        // Insertar el nuevo colaborador
        $result = DB::table('graficadorusuario')->insert([
            'IdGraficador' => $request->graficadorId,
            'Contador' => 1,
            'IdUser' => $user->id,
            'TipoUsuario' => 2,  // Tipo de usuario colaborador
            'Fecha' => now()->toDateString(),
            'Hora' => now()->toTimeString(),
            'Estado' => 1,  // Estado pendiente
        ]);
    
        return response()->json(['success' => $result]);
    }
    

  // Método que lista todas las gráficas
    public function PortalGraficador()
    { 
      // Pasar los datos a la vista
      return view('graficadores.portalgraficador');
    }
    public function show($id)
    {
        $graficador = Graficador::findOrFail($id);
        return view('graficadores.show', compact('graficador'));
    }
    
    // Mostrar formulario para importar imagen

    public function mostrarFormularioImportar()
    {
        return view('graficadores.importador');
    }




    public function procesarImagen(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:4096',
        ]);

        $path = $request->file('imagen')->store('public/imagenes');
        $imagePath = Storage::path($path);

        $client = new RekognitionClient([
            'version' => 'latest',
            'region'  => config('services.rekognition.region'),
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        $imageBytes = file_get_contents($imagePath);

        try {
            $result = $client->detectLabels([
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
                'MaxLabels'     => 10,
                'MinConfidence' => 70,
            ]);

            $labels = collect($result['Labels'])
                ->pluck('Name')
                ->implode(', ');

            $descripcion = 'Etiquetas detectadas: ' . $labels;
            $descripcion = $this->consultarGemini($descripcion);

        } catch (\Exception $e) {
            $descripcion = 'Error al comunicarse con AWS Rekognition: ' . $e->getMessage();
        }
        return response($descripcion)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', "attachment; filename=\"archivo.dart\"")
        ->header('Content-Length', strlen($descripcion));

        //return redirect()->route('graficador.importar')->with([
        //    'descripcion' => $descripcion,
        //    'imagen_url' => Storage::url($path)
        //]);
    }


    public function consultarGemini(string $mensajeUsuario): string
    {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBYaWFDswdP_2nRRCufHEy2uf1k42Ofo8s', [
            'contents' => [
                [
                    'parts' => [
                        'text' => "Genera SOLO el código Flutter para una pantalla basada en esta descripción. No agregues explicaciones ni texto adicional. Devuélvelo en formato de código listo para copiar:\n" . $mensajeUsuario                        
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sin respuesta generada.';
        }

        return 'Error al consultar la API de Gemini: ' . $response->status();
    }



}

