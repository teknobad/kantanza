const container = document.getElementById('3dOutfit');
const scene = new THREE.Scene();

// Kamera
const camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
camera.position.set(0, 1.6, 3);

// Renderer
const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setSize(container.clientWidth, container.clientHeight);
container.appendChild(renderer.domElement);

// Işıklar
const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 1);
hemiLight.position.set(0, 20, 0);
scene.add(hemiLight);

const dirLight = new THREE.DirectionalLight(0xffffff, 1);
dirLight.position.set(5, 10, 5);
scene.add(dirLight);

// GLTF Loader
const loader = new THREE.GLTFLoader();
let baseCharacter, parts = {};

// Karakter yükleme (placeholder)
loader.load('https://threejs.org/examples/models/gltf/RobotExpressive/RobotExpressive.glb', gltf => {
    baseCharacter = gltf.scene;
    baseCharacter.scale.set(0.01, 0.01, 0.01); // modeli uygun boyuta getir
    scene.add(baseCharacter);
    animate();
}, undefined, err => console.error("Karakter yüklenemedi:", err));

// Animasyon döngüsü
function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
}

// Butonlarla kıyafet değiştirme
document.querySelectorAll('.outfit-controls button').forEach(btn => {
    btn.addEventListener('click', () => {
        const part = btn.dataset.part;
        const model = btn.dataset.model;

        if (parts[part]) scene.remove(parts[part]);

        loader.load(model, gltf => {
            parts[part] = gltf.scene;
            parts[part].scale.set(0.01, 0.01, 0.01); // uygun boyut
            scene.add(parts[part]);
        });
    });
});

// Responsive
window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
});
