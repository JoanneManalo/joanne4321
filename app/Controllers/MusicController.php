<?php

namespace App\Controllers;

use App\Models\MusicModel;
use App\Models\PlaylistModel;
use App\Models\MusicPlaylist;

use App\Controllers\BaseController;

class MusicController extends BaseController
{
    private $playlist;
    private $musics;
    private $tracks;

    public function __construct()
    {
        $this->playlist = new PlaylistModel();
        $this->musics = new MusicModel();
        $this->tracks = new MusicPlaylist();
    }
    public function index()
    {
        $data['playlist'] = $this->playlist->findAll();
        $data['musics'] = $this->musics->findAll();
        return view('musicplayer', $data);
    }
    public function create()
    {
        $data = [
            'name' => $this->request->getPost('name')
        ];
        $this->playlist->insert($data);
        return redirect()->to('/musicplayer');
    }

    public function playlist($id)
    {
        $playlist = $this->playlist->find($id);
    
        if ($playlist) {
            $tracks = $this->tracks->where('playlist_id', $id)->findAll();
            $musics = [];
            foreach ($tracks as $track) {
                $musicItem = $this->musics->find($track['music_id']);
                if ($musicItem) {
                    $musics[] = $musicItem;
                }
            }
            $data = [
                'playlist' => $playlist,
                'musics' => $musics,
                'playlist' => $this->playlist->findAll(),
                'tracks' => $tracks,
            ];
    
            
    
            return view('musicplayer', $data);
        } else {
           return redirect()->to('/musicplayer');
        }
    }
    

    public function search()
    {
        $search = $this->request->getGet('title');
        $musicResults = $this->musics->like('title', '%' . $search . '%')->findAll();
        $data = [
            'playlist' => $this->playlist->findAll(),
            'musics' => $musicResults,
        ];
        return view('musicplayer', $data);
    }
    public function add()
    {

        $musicID = $this->request->getPost('musicID');
        $playlistID = $this->request->getPost('playlist');

        $data = [
            'playlist_id' => $playlistID,
            'music_id' => $musicID,
        ];
        $this->tracks->insert($data);
        return redirect()->to('/musicplayer');
    }

    public function upload()
    {
        $file = $this->request->getFile('file');
        $title = $this->request->getPost('title');
        $artist = $this->request->getPost('artist');
        $newName = $title . '_' . $artist . '.' . 'mp3';
        $file->move(ROOTPATH . 'public/', $newName);
        $data = [
            'title' => $title,
            'artist' => $artist,
            'file_path' => $newName
        ];
        $this->musics->insert($data);
        return redirect()->to('/musicplayer');
    }
    
}
