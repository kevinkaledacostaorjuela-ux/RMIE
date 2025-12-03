// Test de Selenium sin dependencia de alerts - Versión robusta
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import static org.junit.Assert.*;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.NoSuchElementException;
import java.time.Duration;
import java.util.*;

public class SubcategoriaTestRobusto {
    private WebDriver driver;
    private WebDriverWait wait;
    private JavascriptExecutor js;
    private String testSubcategoryName = "TestSelenium" + System.currentTimeMillis();
    
    @Before
    public void setUp() {
        driver = new FirefoxDriver();
        js = (JavascriptExecutor) driver;
        wait = new WebDriverWait(driver, Duration.ofSeconds(15));
    }
    
    @After
    public void tearDown() {
        if (driver != null) {
            driver.quit();
        }
    }
    
    @Test
    public void testSubcategoriaFlujoCompleto() {
        try {
            // 1. Navegar al dashboard
            System.out.println("🚀 Iniciando test de subcategorías...");
            driver.get("http://localhost/RMIE/app/views/dashboard.php");
            
            // 2. Ir a subcategorías
            WebElement subcatBtn = wait.until(ExpectedConditions.elementToBeClickable(
                By.cssSelector(".row:nth-child(2) > .col-lg-3:nth-child(3) .btn")));
            subcatBtn.click();
            System.out.println("✅ Navegando a subcategorías");
            
            // 3. Crear nueva subcategoría
            WebElement createBtn = wait.until(ExpectedConditions.elementToBeClickable(
                By.cssSelector(".me-2")));
            createBtn.click();
            System.out.println("✅ Abriendo formulario de creación");
            
            // 4. Llenar formulario
            WebElement nombreField = wait.until(ExpectedConditions.visibilityOfElementLocated(By.id("nombre")));
            nombreField.sendKeys(testSubcategoryName);
            
            WebElement descField = driver.findElement(By.id("descripcion"));
            descField.sendKeys("Descripción de prueba automatizada");
            
            // 5. Seleccionar categoría (primera disponible)
            WebElement dropdown = driver.findElement(By.id("id_categoria"));
            List<WebElement> options = dropdown.findElements(By.tagName("option"));
            if (options.size() > 1) {
                options.get(1).click(); // Seleccionar primera categoría real (no el placeholder)
            }
            
            // 6. Enviar formulario
            WebElement createSubmitBtn = driver.findElement(By.cssSelector(".btn-create"));
            createSubmitBtn.click();
            System.out.println("✅ Subcategoría creada: " + testSubcategoryName);
            
            // 7. Esperar a volver al index y verificar creación
            wait.until(ExpectedConditions.urlContains("index"));
            Thread.sleep(2000); // Dar tiempo para que cargue la lista
            
            // 8. Cambiar a vista de tabla si no está activa
            try {
                WebElement tableBtn = driver.findElement(By.id("btnTable"));
                tableBtn.click();
                Thread.sleep(1000);
            } catch (NoSuchElementException e) {
                System.out.println("ℹ️ Vista de tabla ya activa o no disponible");
            }
            
            // 9. Buscar la subcategoría en la lista
            boolean found = false;
            List<WebElement> rows = driver.findElements(By.cssSelector("table tbody tr"));
            WebElement editButton = null;
            WebElement deleteButton = null;
            
            for (WebElement row : rows) {
                List<WebElement> cells = row.findElements(By.tagName("td"));
                if (cells.size() > 0 && cells.get(0).getText().contains(testSubcategoryName)) {
                    found = true;
                    System.out.println("✅ Subcategoría encontrada en la lista");
                    
                    // Encontrar botones de acción
                    List<WebElement> actionBtns = row.findElements(By.cssSelector(".btn"));
                    if (actionBtns.size() >= 2) {
                        editButton = actionBtns.get(0);
                        deleteButton = actionBtns.get(1);
                    }
                    break;
                }
            }
            
            assertTrue("❌ La subcategoría no se encontró en la lista", found);
            assertNotNull("❌ No se encontró el botón de editar", editButton);
            assertNotNull("❌ No se encontró el botón de eliminar", deleteButton);
            
            // 10. Probar edición
            editButton.click();
            System.out.println("✅ Abriendo edición");
            
            WebElement editNombreField = wait.until(ExpectedConditions.visibilityOfElementLocated(By.id("nombre")));
            editNombreField.clear();
            String nombreEditado = testSubcategoryName + "_EDITADO";
            editNombreField.sendKeys(nombreEditado);
            
            WebElement updateBtn = driver.findElement(By.cssSelector(".btn-update"));
            updateBtn.click();
            System.out.println("✅ Subcategoría editada");
            
            // 11. Esperar a volver al index
            wait.until(ExpectedConditions.urlContains("index"));
            Thread.sleep(2000);
            
            // 12. Buscar el botón de eliminar de nuevo
            rows = driver.findElements(By.cssSelector("table tbody tr"));
            deleteButton = null;
            
            for (WebElement row : rows) {
                List<WebElement> cells = row.findElements(By.tagName("td"));
                if (cells.size() > 0 && cells.get(0).getText().contains(nombreEditado)) {
                    List<WebElement> actionBtns = row.findElements(By.cssSelector(".btn"));
                    if (actionBtns.size() >= 2) {
                        deleteButton = actionBtns.get(1);
                    }
                    break;
                }
            }
            
            assertNotNull("❌ No se encontró el botón de eliminar después de editar", deleteButton);
            
            // 13. Proceder con eliminación
            deleteButton.click();
            System.out.println("✅ Iniciando proceso de eliminación");
            
            // 14. Esperar página de confirmación
            wait.until(ExpectedConditions.urlContains("delete"));
            System.out.println("✅ En página de confirmación de eliminación");
            
            // 15. Confirmar eliminación
            WebElement confirmBtn = wait.until(ExpectedConditions.elementToBeClickable(
                By.linkText("Confirmar Eliminación")));
            
            // 16. Usar JavaScript para hacer el clic y capturar el resultado
            js.executeScript("arguments[0].click();", confirmBtn);
            System.out.println("✅ Confirmación de eliminación enviada");
            
            // 17. Esperar resultado - puede ser alert o redirección directa
            boolean alertHandled = false;
            try {
                // Intentar manejar alert si aparece
                wait.until(ExpectedConditions.alertIsPresent());
                String alertText = driver.switchTo().alert().getText();
                System.out.println("📢 Mensaje de confirmación: " + alertText);
                driver.switchTo().alert().accept();
                alertHandled = true;
            } catch (Exception e) {
                System.out.println("ℹ️ No hubo alert, verificando redirección directa");
            }
            
            // 18. Esperar regreso al index
            wait.until(ExpectedConditions.urlContains("index"));
            Thread.sleep(3000); // Dar tiempo extra para que se actualice la lista
            
            // 19. Verificación final - la subcategoría no debe existir
            rows = driver.findElements(By.cssSelector("table tbody tr"));
            boolean stillExists = false;
            
            for (WebElement row : rows) {
                List<WebElement> cells = row.findElements(By.tagName("td"));
                if (cells.size() > 0 && (cells.get(0).getText().contains(testSubcategoryName) || 
                    cells.get(0).getText().contains(nombreEditado))) {
                    stillExists = true;
                    break;
                }
            }
            
            assertFalse("❌ La subcategoría aún existe después de eliminar", stillExists);
            System.out.println("✅ Subcategoría eliminada correctamente - Test EXITOSO");
            
        } catch (Exception e) {
            System.err.println("❌ Error durante el test: " + e.getMessage());
            e.printStackTrace();
            fail("Test falló con excepción: " + e.getMessage());
        }
    }
}