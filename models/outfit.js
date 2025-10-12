const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(50, 1, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true });
renderer.setSize(document.getElementById('3dOutfit').clientWidth, 500);
document.getElementById('3dOutfit').appendChild(renderer.domElement);

const light = new THREE.HemisphereLight(0xffffff,0x444444,1);
light.position.set(0,20,0);
scene.add(light);

const loader = new THREE.GLTFLoader();
let baseCharacter, parts = {};

loader.load('models/base_character.glb', gltf => {
    baseCharacter = gltf.scene;
    scene.add(baseCharacter);
    animate();
});

function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
}

camera.position.set(0,1.6,3);

document.querySelectorAll('.outfit-controls button').forEach(btn => {
    btn.addEventListener('click', () => {
        const part = btn.dataset.part;
        const model = btn.dataset.model;
        if(parts[part]) scene.remove(parts[part]);
        loader.load(`models/${model}`, gltf => {
            parts[part] = gltf.scene;
            scene.add(parts[part]);
        });
    });
});
