<?php
declare(strict_types=1);
namespace CodeShopping\Models;

use Carbon\Carbon;
use DB;
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

  const DISK = 'public';

  /**
   * @param $productId
   * @return string
   */
  public static function getPhotoPath($productId)
  {
    $path = self::DIR_PRODUCTS;
    return "{$path}/{$productId}";
  }

  public static function createDisk()
  {
    return Storage::disk(self::DISK);
  }

  /**
   * @param string $photoPath
   * @param UploadedFile $file
   * @return string
   */
  public static function createPhotoFile(string $photoPath, UploadedFile $file): string
  {
    $disk = self::createDisk();
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
      DB::beginTransaction();
      $photos = self::createPhotoModels($productId, $uploadedFiles);
      DB::commit();
      return new Collection($photos);
    } catch (\Exception $e)
    {
      self::deleteFiles($productId,$uploadedFiles);
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Substituir a foto do produto no banco de dados e no diretório de fotos
   * @param ProductPhoto $photo
   * @param array $file
   * @return ProductPhoto
   * @throws \Exception
   */
  public static function substitutePhotoAndFile(ProductPhoto $photo, $file)
  {
    $uploadedFile = [];
    try
    {
      list($originalFile, $renamedFile) = self::renamePhotoFile($photo);
      $uploadedFile = self::uploadFiles($photo->product_id, [$file]);
      DB::beginTransaction();
      $photo->file_name = implode('',$uploadedFile);
      $photo->save();
      self::deleteRenamedFile($renamedFile);
      DB::commit();
      return $photo;
    } catch (\Exception $e)
    {
      DB::rollBack();
      self::returnRenamedFile($renamedFile,$originalFile);
      throw $e;
    }
  }

  /**
   * Remove um arquivo renomeado, durante o processo de substituição ou exclusão
   *
   * @param string $renamedFile Nome do arquivo renomeado
   * @return void
   */
  private static function deleteRenamedFile($renamedFile): void
  {
    self::createDisk()->delete($renamedFile);
  }

  /**
   * Retorna um arquivo renomeado, ao seu nome original em caso de falha durante o processo de substituição ou exclusão
   *
   * @param string $renamedFile Nome do arquivo renomeado
   * @param string $originalFile Nome do arquivo original
   * @return void
   */
  private static function returnRenamedFile($renamedFile, $originalFile ): void
  {
    self::createDisk()->move($renamedFile, $originalFile);
  }

  /**
   * Remover a foto do produto no banco de dados e no diretório de fotos
   * @param ProductPhoto $photo
   * @param array $file
   * @return ProductPhoto
   * @throws \Exception
   */
  public function deletePhotoAndFile() : bool
  {
    try
    {
      list($originalFile, $renamedFile) = self::renamePhotoFile($this);
      DB::beginTransaction();
      $return = $this->delete();
      self::deleteRenamedFile($renamedFile);
      DB::commit();
      return $return;
    } catch (\Exception $e)
    {
      DB::rollBack();
      self::returnRenamedFile($renamedFile,$originalFile);
      throw $e;
    }
  }

  /**
   * @param int $productId
   * @param array $files
   */
  public static function deleteFiles(int $productId, array $files)
  {
    $disk = self::createDisk();
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
    return $this->belongsTo(Product::class)->withTrashed();
  }

  /**
   * Renomeia os arquivos em disco para substituição ou remoção
   * @param ProductPhoto $photo
   * @return array
   */
  private static function renamePhotoFile(ProductPhoto $photo): array
  {
    $photoPath = self::getPhotoPath($photo->product_id);
    $dateTime = Carbon::now()->format('YmdHis');
    $originalFile = "{$photoPath}/{$photo->file_name}";
    $renamedFile = "{$photoPath}/{$photo->file_name}.{$dateTime}";
    self::createDisk()->move($originalFile, $renamedFile);
    return [$originalFile, $renamedFile];
  }
}
