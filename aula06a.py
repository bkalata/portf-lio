n1 = input('Digite o primeiro numero: ')
print(type(n1))
n2 = input('Segundo: ')
print(n2.isnumeric())
resultado = int(n1) + int(n2)

#print ('A soma entre', n1 , '+', n2,'tem como resultado:', resultado)

#print('A soma total é: {} '.format(resultado))

print('A soma entre {} e {} da um total de {}'.format(n1, n2, resultado))

