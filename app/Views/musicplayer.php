<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/include/styles.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('public/include/js/script.js') ?>"></script>

    <style>
        body {
            background-image: url(https://diymusician.cdbaby.com/wp-content/uploads/2021/11/How-to-Use-Spotify-Canvas-for-Cool-Video-Features-header.gif);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: black;
            font-family: "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 15px;
            text-align: center;
            background-color: #f5f5f5;
            padding: 50px;

        
        }

        h1 {
            color: white;
        }

        #player-container {
            max-width: 100px;
            margin: 0 auto;
            padding: 200px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 8, 0, 0.2);
        }

        audio {
            width: 50%;
        }

        #playlist {
            list-style: none;
            padding: 0;
        }

        #playlist li {
            cursor: pointer;
            padding: 10px;
            background-color: rgba(70,70,70,.7);
            margin: 20px 0;
            transition: background-color 0.2s ease-in-out;

        }

        #playlist li:hover {
            background-color: white;
        }

        #playlist li.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>

    <h1>Music Player</h1>
    <br>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <br>
                    <ul class="list-unstyled mt-3">
                        <?php foreach ($playlist as $play) : ?>
                            <li>
                                <a href="/playlist/<?= $play['id'] ?>">
                                    <?= $play['name'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <br>
                </div>
                <div class="modal-footer">
                    <a href="#" data-bs-dismiss="modal">Close</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#createPlaylist">Create New</a>
                </div>
            </div>
        </div>
    </div>
    <form action="/search" method="get">
        <div class="input-group ">
            <input type="search" name="title" class="form-control" placeholder="Search for a song" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <br>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        My Playlist
    </button>
    <br>
    <br>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSong">
        Upload a Song
    </button>
    <br>
    <br>
    <h1 id="currentTrackTitle"></h1>
    <br>
    <audio id="audio" controls autoplay type="audio/mpeg"></audio>

    <ul class="list-unstyled mt-3" id="playlist">
        <?php foreach ($musics as $mus) : ?>
            <li class="align-items-center" data-src="/<?= $mus['file_path'] ?>">
                <a href="#" id="musics" class="play-link" data-musics-id="<?= $mus['id'] ?>">
                    <?= $mus['title'] ?>
                </a>
                <button class="open-modal btn btn-primary" data-target="#mymodal" data-toggle="modal" data-musics-id="<?= $mus['id'] ?>">
                +   
                </button>
                
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Select from playlist</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/add" method="post">
                        <!-- <p id="modalData"></p> -->
                        <input type="hidden" id="musicID" name="musicID" value="">
                        <select name="playlist" class="form-control">
                            <?php foreach ($playlist as $play) : ?>
                                <option value="<?= $play['id'] ?>"><?= $play['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" name="add">
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Get references to the modal and form elements
            const modal = $("#myModal");
            const musicID = $("#musicID");

            // Function to open the modal with the specified music ID
            function openModalWithMusicID(dataId) {
                musicID.val(dataId);
                modal.modal("show"); // Use Bootstrap's modal("show") to display the modal
            }

            // Add click event listeners to all open-modal buttons
            $(".open-modal").click(function(event) {
                event.preventDefault(); // Prevent the default behavior of the anchor link
                const musicId = $(this).data("musics-id");
                openModalWithMusicID(musicId);
            });

            // When the user clicks the close button or outside the modal, close it
            modal.on("hide.bs.modal", function() {
                musicID.val(""); // Clear the musicID input when closing the modal
            });
        });
    </script>

    <script>
        const audio = document.getElementById('audio');
        const playlist = document.getElementById('playlist');
        const playlistItems = playlist.querySelectorAll('li');
        const currentTrackTitle = document.getElementById('currentTrackTitle');
        let currentTrack = 0;

        function playTrack(trackIndex) {
            if (trackIndex >= 0 && trackIndex < playlistItems.length) {
                const track = playlistItems[trackIndex];
                const trackSrc = track.getAttribute('data-src');
                const trackTitle = track.textContent;
                audio.src = trackSrc;
                audio.play();
                currentTrack = trackIndex;
                currentTrackTitle.textContent = trackTitle;
            }
        }

        function nextTrack() {
            currentTrack = (currentTrack + 1) % playlistItems.length;
            playTrack(currentTrack);
        }

        function previousTrack() {
            currentTrack = (currentTrack - 1 + playlistItems.length) % playlistItems.length;
            playTrack(currentTrack);
        }

        playlistItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                playTrack(index);
            });
        });

        audio.addEventListener('ended', () => {
            nextTrack();
        });

        playTrack(currentTrack);
    </script>

    <!-- Create Playlist -->
    <div class="modal fade" id="createPlaylist" tabindex="-1" aria-labelledby="createPlaylistLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlaylistLabel">Create Playlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createPlaylistForm" action="/create" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Playlist Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-success" value="Create">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#createPlaylistButton').click(function() {
                var name = $('#name').val();
                $('#createPlaylist').modal('hide');
            });
        });
    </script>
    <!-- upload-->

    <div class="modal fade" id="uploadSong" tabindex="-1" aria-labelledby="uploadSongLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSongLabel">Upload a Song</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="\upload" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="artist" class="form-label">Artist</label>
                            <input type="text" class="form-control" id="artist" name="artist" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Song File (MP3)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".mp3" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>