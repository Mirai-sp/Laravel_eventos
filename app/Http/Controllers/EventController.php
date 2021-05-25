<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{

    private function uploadImage(Request $request) {
        $requestImage = $request->image;
        $extension    = $requestImage->extension();
        $imageName    = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $requestImage->move(public_path('img/events'), $imageName);
        return $imageName;
    }
    public function index() {
        $search = request('search');

        if($search) {

            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        } else {
            $events = Event::all();
        }        
    
        return view('welcome',['events' => $events, 'search' => $search]);
    }

    public function create() {
        return view('events.create');
    }

    public function store(Request $request) {
        $event              = new Event;
        $event->title       = $request->title;
        $event->city        = $request->city;
        $event->date        = $request->date;
        $event->private     = $request->private;
        $event->description = $request->description;
        $event->items       = $request->items;

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid())             
            $event->image = $this->doUpload();          
        
        $user           = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');;
    }

    public function show($id) {        
        $event = Event::findOrFail($id);                
        return view('events.show', ['event' => $event]);
        
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);       
        // $event = Event::with('user')->findOrFail($id);               
        // dd($event->user->contains(Auth()->user()));		
        
        if ($event->user->id === auth()->user()->id) {
            $imageFile = public_path('img/events/') . $event->image;
            if (file::exists($imageFile))
                File::delete($imageFile);                        
            
            $event->delete();
            return redirect(Route('dashboard'))->with('msg', 'O evento foi excluído com sucesso!');
        }
        else
            return redirect(Route('dashboard'))->withErrors(['nouid' => 'Você não tem acesso para fazer esta exclusão']);
    }

    public function edit($id) {
        $event = Event::findOrFail($id);       
        if ($event->user->id === auth()->user()->id) {
            return view('events.edit', ['event' => $event]);
        }
        else
            return redirect(Route('dashboard'))->withErrors(['nouid' => 'Você não tem acesso para fazer esta edição']);
    }

    public function update(Request $request) {
        $event = Event::findOrFail($request->id);
        if ($event->user->id === auth()->user()->id) {
            
            $event->title       = $request->title;
            $event->city        = $request->city;
            $event->date        = $request->date;
            $event->private     = $request->private;
            $event->description = $request->description;
            $event->items       = $request->items;            
            // Image Upload
            if($request->hasFile('image') && $request->file('image')->isValid()) {
                //deletar imagem antiga
                $imageFile = public_path('img/events/') . $event->image;
                if (file::exists($imageFile))
                    File::delete($imageFile); 
                $event->image = $this->uploadImage($request);
            }    
            $event->save();
            return redirect(Route('dashboard'))->with('msg', 'Evento editado com sucesso!');
        }
        else
            return redirect(Route('dashboard'))->withErrors(['nouid' => 'Você não tem acesso para fazer esta edição']);
    
    }

    public function joinEvent($id) {
        $event = Event::findOrFail($id);
        
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);        

        return redirect(Route('dashboard'))->with('msg', 'Sua presença está confirmada no evento ' . $event->title);

    }

    
    public function leaveEvent($id) {
        $event = Event::findOrFail($id);

        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);        

        return redirect(Route('dashboard'))->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);

    }
}
