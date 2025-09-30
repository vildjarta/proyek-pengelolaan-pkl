<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Dosen Pembimbing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .tag-container {
      border: 1px solid #ced4da;
      padding: 6px;
      border-radius: 6px;
      min-height: 46px;
      display: flex;
      flex-wrap: wrap;
      cursor: text;
    }
    .tag {
      background-color: #0d6efd;
      color: white;
      padding: 4px 8px;
      border-radius: 12px;
      margin: 3px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .tag i {
      cursor: pointer;
    }
    .tag-input {
      border: none;
      flex: 1;
      min-width: 120px;
      outline: none;
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <a href="{{ route('datadosenpembimbing.index') }}" class="text-decoration-none text-dark">
    <i class="bi bi-arrow-left-circle fs-3"></i>
  </a>

  <h2 class="mb-3 mt-3">Edit Dosen Pembimbing</h2>

  <form action="{{ route('datadosenpembimbing.update', $item->id_pembimbing) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="NIP" value="{{ $item->NIP }}" class="form-control" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Dosen</label>
      <input type="text" name="nama" value="{{ $item->nama }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" value="{{ $item->email }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Mahasiswa</label>
      <div id="tagInputContainer" class="tag-container">
        <input type="text" id="tagInput" class="tag-input" placeholder="Ketik nama lalu tekan Enter...">
      </div>
      <input type="hidden" name="nama_mahasiswa" id="hiddenNamaMahasiswa">
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>

<script>
  const tagContainer = document.getElementById('tagInputContainer');
  const tagInput = document.getElementById('tagInput');
  const hiddenInput = document.getElementById('hiddenNamaMahasiswa');
  let tags = [];

  // Ambil data lama dari database
  const existingData = "{{ $item->nama_mahasiswa ?? '' }}";
  if (existingData) {
    tags = existingData.split(',').map(t => t.trim()).filter(Boolean);
  }

  function updateHiddenInput() {
    hiddenInput.value = tags.join(',');
  }

  function addTag(text) {
    if (text && !tags.includes(text)) {
      tags.push(text);
      renderTags();
      updateHiddenInput();
    }
  }

  function removeTag(index) {
    tags.splice(index, 1);
    renderTags();
    updateHiddenInput();
  }

  function renderTags() {
    tagContainer.innerHTML = '';
    tags.forEach((tag, index) => {
      const tagEl = document.createElement('span');
      tagEl.className = 'tag';
      tagEl.innerHTML = `${tag} <i class="bi bi-x-circle" onclick="removeTag(${index})"></i>`;
      tagContainer.appendChild(tagEl);
    });
    tagContainer.appendChild(tagInput);
    tagInput.value = '';
  }

  renderTags();

  tagInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const text = tagInput.value.trim();
      if (text) addTag(text);
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
