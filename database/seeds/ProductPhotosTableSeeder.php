<?php
declare (strict_types=1);
use CodeShopping\Models\Product;
use CodeShopping\Models\ProductPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Collection;

class ProductPhotosTableSeeder extends Seeder
{
  /** @var Collection $allFakerPhotos */
  private $allFakerPhotos;
  private $fakerPhotosPath = 'faker/product_photos';
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /** @var \Illuminate\Database\Eloquent\Collection $products */
    $this->allFakerPhotos = $this->getFakerPhotos();
    $products = Product::all();
    $self = $this;
    $products->each(function ($product) use ($self)
    {
      $self->deleteProductPhotoDir($product);
      $self->makeProductPhotoDir($product);
      $self->createPhotosModels($product);
    });
  }
  private function getFakerPhotos(): Collection
  {
    $files = Storage::disk('local')->allFiles($this->fakerPhotosPath);
    return collect($files);
  }
  private function deleteProductPhotoDir(Product $product)
  {
    $dir = ProductPhoto::getPhotoPath($product->id);
    Storage::disk('public')->deleteDirectory($dir);
  }
  private function  makeProductPhotoDir(Product $product)
  {
    $dir = ProductPhoto::getPhotoPath($product->id);
    Storage::disk('public')->makeDirectory($dir);
  }
  private function createPhotosModels(Product $product)
  {
    foreach (range(1,5) as $v)
    {
      $this->createPhotoModel($product);
    }
  }
  private function createPhotoModel(Product $product)
  {
    $photo = ProductPhoto::create(
      [
        'product_id' => $product->id,
        'file_name' => 'teste.jpg'
      ]
    );
    $this->generatePhoto($photo);
  }
  private function generatePhoto(ProductPhoto $photo)
  {
    $photo->file_name = $this->uploadPhoto($photo->product_id);
    $photo->save();
  }
  private function uploadPhoto($productId) : string
  {
    $fakerFile = $this->allFakerPhotos->random();
    $photoPath = ProductPhoto::getPhotoPath($productId);
    $localStorage = Storage::disk('local');
    $file = new File($localStorage->path($fakerFile));
    // Para retornar somente o nome do arquivo porque o método 'putFile' retorna o diretório relativo a 'public'...
    $publicPhoto = substr(strrchr(Storage::disk('public')->putFile($photoPath,$file),'/'),1);
    return $publicPhoto;
  }

}
