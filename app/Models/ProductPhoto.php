<?php
declare(strict_types=1);
namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class ProductPhoto extends Model
{
  protected $fillable= ['product_id','file_name'];

  const CREATED_AT = 'dtCriacao';
  const UPDATED_AT = 'dtAtualizacao';
  const DIR_PRODUCTS = 'products';

  /**
   * @param $productId
   * @return string
   */
  public static function getPhotoPath($productId)
  {
    $path = self::DIR_PRODUCTS;
    return "{$path}/{$productId}";
  }

  /**
   * @param string $photoPath
   * @param UploadedFile $file
   * @return string
   */
  public static function createPhotoFile(string $photoPath, UploadedFile $file): string
  {
    $disk = Storage::disk('public');
    return substr(strrchr($disk->putFile($photoPath,$file),'/'),1);
  }

  /**
   * @param int $productId
   * @param array $files
   * @return Collection
   * @throws \Exception
   */
  public static function createWithPhotoFiles(int $productId, array $files) : Collection
  {
    try
    {
      $uploadedFiles = [];
      $uploadedFiles = self::uploadFiles($productId,$files);
      \DB::beginTransaction();
      $photos = self::createPhotoModels($productId, $uploadedFiles);
//      throw new Exception('Vamos interromper a bagaça');
      \DB::commit();
      return new Collection($photos);
    } catch (\Exception $e)
    {
      self::deleteFiles($productId,$uploadedFiles);
      \DB::rollBack();
      throw $e;
    }
  }
  public static function substitutePhotoAndFile(ProductPhoto $photo, array $file)
  {
    $uploadedFile = [];
    self::deleteFiles($photo->product_id,array($photo->file_name));
    $uploadedFile = self::uploadFiles($photo->product_id, $file);
    $photo->file_name = implode('',$uploadedFile);
    $photo->save();
    return $photo;
  }

  public static function removePhotoAndFile(ProductPhoto $photo)
  {

    $photo->delete();
    ProductPhoto::deleteFiles($photo->product_id,array($photo->file_name));
  }

  /**
   * @param int $productId
   * @param array $files
   */
  public static function deleteFiles(int $productId, array $files)
  {
    $disk = Storage::disk('public');
    foreach ($files as $file)
    {
      $path = self::getPhotoPath($productId);
      $photoFileName =  "{$path}/{$file}";
      if ($disk->exists($photoFileName))
      {
        $disk->delete($photoFileName);
      }
    }
  }

  /**
   * @param int $productId
   * @param array $files
   * @return array
   * @throws \Exception
   */
  public static function uploadFiles(int $productId, array $files) : array
  {
    $dir = self::getPhotoPath($productId);
    $uploaded = [];
    try
    {
      /** @var UploadedFile $file */
      foreach ($files as $file)
      {
        // Armazena os nomes dos arquivos gerados...
        $uploaded[] = self::createPhotoFile($dir,$file);
      }
    }
    catch (\Exception $e)
    {
      throw $e;
    }
    finally
    {
      return $uploaded;
    }
  }

  /**
   * @param int $productId
   * @param array $files
   * @return array
   */
  private static function createPhotoModels(int $productId, array $files) : array
  {
    $photos = [];
    foreach ($files as $file)
    {
      $photos[] = self::create([
        'file_name' => $file,
        'product_id' => $productId
      ]);
    }
    return $photos;
  }
  /**
   * @return string
   */
  public function getPhotoUrlAttribute()
  {
    $path = self::getPhotoPath($this->product_id);
    return asset("storage/{$path}/{$this->file_name}");
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
