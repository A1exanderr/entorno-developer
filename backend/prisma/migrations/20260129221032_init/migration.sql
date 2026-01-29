/*
  Warnings:

  - The `estado` column on the `Usuario` table would be dropped and recreated. This will lead to data loss if there is data in the column.

*/
-- CreateEnum
CREATE TYPE "Estado" AS ENUM ('activo', 'desactivo');

-- AlterTable
ALTER TABLE "Usuario" DROP COLUMN "estado",
ADD COLUMN     "estado" "Estado" NOT NULL DEFAULT 'activo';
